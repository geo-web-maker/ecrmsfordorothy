<?php

namespace App\Services;
use AfricasTalking\SDK\AfricasTalking;

class SmsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function send(string $phone, string $message): void {
        $at  = new AfricasTalking(env('AT_USERNAME'), env('AT_API_KEY'));
        $sms = $at->sms();
        $sms->send(['to' => $phone, 'message' => $message, 'from' => env('AT_FROM')]);
    }

}
