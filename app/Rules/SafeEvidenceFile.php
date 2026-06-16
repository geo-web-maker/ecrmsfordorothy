<?php

namespace App\Rules;

use App\Services\SecureEvidenceUploader;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class SafeEvidenceFile implements ValidationRule
{
    public function __construct(private bool $allowVideo = true) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        if (! $value instanceof UploadedFile) {
            $fail('Invalid upload.');

            return;
        }

        try {
            app(SecureEvidenceUploader::class)->validate($value, $this->allowVideo);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = method_exists($e, 'errors') ? $e->errors() : [];
            $message = $errors['evidence'][0] ?? $e->getMessage() ?? 'Invalid file upload.';
            $fail($message);
        }
    }
}
