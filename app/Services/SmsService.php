<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SmsService
{
    public function isConfigured(): bool
    {
        return filled(config('services.textbee.api_key'))
            && filled(config('services.textbee.device_id'));
    }

    public function send(string $phone, string $message): void
    {
        $apiKey   = config('services.textbee.api_key');
        $deviceId = config('services.textbee.device_id');
        $baseUrl  = rtrim((string) config('services.textbee.base_url'), '/');

        $response = Http::timeout((int) config('services.textbee.timeout', 30))
            ->withHeaders([
                'x-api-key' => $apiKey,
                'Accept'    => 'application/json',
            ])
            ->post("{$baseUrl}/gateway/devices/{$deviceId}/send-sms", [
                'recipients' => [$phone],
                'message'    => $message,
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'TextBee SMS failed: '.$response->body(),
                $response->status()
            );
        }
    }
}
