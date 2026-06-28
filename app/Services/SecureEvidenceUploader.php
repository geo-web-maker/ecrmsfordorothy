<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;


class SecureEvidenceUploader
{
    private const IMAGE_MIMES = ['image/jpeg', 'image/png'];

    private const VIDEO_MIMES = ['video/mp4', 'video/quicktime', 'video/x-quicktime'];

    private const DANGEROUS_PATTERNS = [
        '/<\?php/i',
        '/<\?=/i',
        '/<script/i',
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        '/on\w+\s*=/i',
    ];

    /**
     * @param  array<int, UploadedFile>  $files
     * @return array<int, array{path: string, file_type: string}>
     */
    public function storeMany(array $files, bool $allowVideo = true): array
    {
        $stored = [];

        foreach ($files as $index => $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }

            try {
                $stored[] = $this->storeOne($file, $allowVideo);
            } catch (ValidationException $e) {
                throw ValidationException::withMessages([
                    "evidence.{$index}" => $e->validator->errors()->first('evidence') ?? 'Invalid file upload.',
                ]);
            }
        }

        return $stored;
    }

    /**
     * Validates the uploaded file and throws ValidationException if invalid.
     *
     * @throws ValidationException
     */
    public function validate(UploadedFile $file, bool $allowVideo = true): void
    {
        $this->assertSafeFilename($file);
        $this->assertNoEmbeddedScripts($file);

        $mime = $this->detectMimeType($file);

        if (in_array($mime, self::IMAGE_MIMES, true)) {
            $this->assertImageMagicBytes($file);

            return;
        }

        if ($allowVideo && in_array($mime, self::VIDEO_MIMES, true)) {
            $this->assertVideoMagicBytes($file);

            return;
        }

        throw ValidationException::withMessages([
            'evidence' => 'Only valid JPEG, PNG' . ($allowVideo ? ', MP4, or MOV' : '') . ' files are allowed.',
        ]);
    }

    /**
     * @return array{path: string, file_type: string}
     */
    public function storeOne(UploadedFile $file, bool $allowVideo = true): array
    {
        $this->validate($file, $allowVideo);

        $mime = $this->detectMimeType($file);

        if (in_array($mime, self::IMAGE_MIMES, true)) {
            return [
                'path' => $this->storeSanitizedImage($file, $mime),
                'file_type' => 'image',
            ];
        }

        return [
            'path' => $this->storeVideo($file, $mime),
            'file_type' => 'video',
        ];
    }

    private function assertSafeFilename(UploadedFile $file): void
    {
        $name = strtolower($file->getClientOriginalName());

        if (str_contains($name, '..') || str_contains($name, "\0")) {
            throw ValidationException::withMessages([
                'evidence' => 'Invalid file name.',
            ]);
        }

        if (preg_match('/\.(php|phtml|phar|html|htm|js|svg|xml|exe|bat|sh|cmd|com|dll)(\.\w+)?$/i', $name)) {
            throw ValidationException::withMessages([
                'evidence' => 'This file type is not allowed.',
            ]);
        }
    }

    private function assertNoEmbeddedScripts(UploadedFile $file): void
    {
        $sample = (string) file_get_contents($file->getRealPath(), false, null, 0, 16384);

        foreach (self::DANGEROUS_PATTERNS as $pattern) {
            if (preg_match($pattern, $sample)) {
                throw ValidationException::withMessages([
                    'evidence' => 'The file contains disallowed content and was rejected.',
                ]);
            }
        }
    }

    private function detectMimeType(UploadedFile $file): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo === false) {
            throw ValidationException::withMessages([
                'evidence' => 'Unable to verify file type.',
            ]);
        }

        $mime = finfo_file($finfo, $file->getRealPath()) ?: '';
        finfo_close($finfo);

        return strtolower($mime);
    }

    private function assertImageMagicBytes(UploadedFile $file): void
    {
        $handle = fopen($file->getRealPath(), 'rb');
        $header = $handle ? fread($handle, 8) : '';
        if ($handle) {
            fclose($handle);
        }

        $isJpeg = str_starts_with($header, "\xFF\xD8\xFF");
        $isPng  = str_starts_with($header, "\x89PNG\r\n\x1A\n");

        if (! $isJpeg && ! $isPng) {
            throw ValidationException::withMessages([
                'evidence' => 'The image file is corrupted or not a valid JPEG/PNG.',
            ]);
        }
    }

    private function assertVideoMagicBytes(UploadedFile $file): void
    {
        $handle = fopen($file->getRealPath(), 'rb');
        $header = $handle ? fread($handle, 12) : '';
        if ($handle) {
            fclose($handle);
        }

        // ISO BMFF (MP4/MOV): bytes 4–7 are 'ftyp'
        if (strlen($header) < 8 || substr($header, 4, 4) !== 'ftyp') {
            throw ValidationException::withMessages([
                'evidence' => 'The video file is corrupted or not a valid MP4/MOV.',
            ]);
        }
    }

    private function storeSanitizedImage(UploadedFile $file, string $mime): string
    {
        $currentBytes = $this->parseMemoryLimit(ini_get('memory_limit'));
        if ($currentBytes !== -1 && $currentBytes < 512 * 1024 * 1024) {
            ini_set('memory_limit', '512M');
        }
    
        $extension    = $mime === 'image/png' ? 'png' : 'jpg';
        $filename     = Str::uuid() . '.' . $extension;
        $relativePath = 'evidence/' . $filename;
    
        try {
            $driver  = extension_loaded('gd') ? new GdDriver() : new ImagickDriver();
            $manager = new ImageManager($driver);
            $image   = $manager->read($file->getRealPath());
    
            // Encode to bytes in memory instead of saving to local path
            $encoded = $extension === 'png'
                ? (string) $image->toPng()
                : (string) $image->toJpeg(85);
    
        } catch (\Throwable $e) {
            Log::error('SecureEvidenceUploader image processing failed', [
                'message' => $e->getMessage(),
                'file'    => $file->getClientOriginalName(),
            ]);
            throw ValidationException::withMessages([
                'evidence' => 'The image could not be processed safely. Please upload a different file.',
            ]);
        }
    
        Storage::disk(config('filesystems.default'))->put($relativePath, $encoded, 'public');
    
        return $relativePath;
    }

    private function storeVideo(UploadedFile $file, string $mime): string
    {
        $extension    = in_array($mime, ['video/quicktime', 'video/x-quicktime'], true) ? 'mov' : 'mp4';
        $relativePath = 'evidence/' . Str::uuid() . '.' . $extension;
    
        Storage::disk(config('filesystems.default'))->putFileAs(
            'evidence',
            $file,
            basename($relativePath),
            'public'
        );
    
        return $relativePath;
    }

    /**
     * Convert a php.ini memory string (e.g. '128M', '1G', '-1') to bytes.
     */
    private function parseMemoryLimit(string $value): int
    {
        $value = trim($value);
        if ($value === '-1') {
            return -1;
        }

        $unit  = strtolower($value[strlen($value) - 1]);
        $bytes = (int) $value;

        return match ($unit) {
            'g'     => $bytes * 1024 * 1024 * 1024,
            'm'     => $bytes * 1024 * 1024,
            'k'     => $bytes * 1024,
            default => $bytes,
        };
    }
}
