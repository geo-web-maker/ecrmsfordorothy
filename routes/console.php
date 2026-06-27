<?php
# app/routes/console.php
# php artisan send-mail

use App\Services\SmsService;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Artisan;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

Artisan::command('sms:test {phone} {message?}', function (string $phone, ?string $message, SmsService $sms) {
    $normalized = PhoneNumber::normalize($phone);
    $body = $message ?? 'NEMA eCRMS: SMS test message.';

    if (! $sms->isConfigured()) {
        $this->error('TextBee SMS is not configured. Set TEXTBEE_API_KEY and TEXTBEE_DEVICE_ID in .env.');

        return 1;
    }

    try {
        $sms->send($normalized, $body);
        $this->info("SMS sent via TextBee to {$normalized}.");

        return 0;
    } catch (\Throwable $e) {
        $this->error('SMS failed: '.$e->getMessage());

        return 1;
    }
})->purpose('Send a test SMS via TextBee');

Artisan::command('send-mail', function () {
    $email = (new MailtrapEmail())
        ->from(new Address('hello@demomailtrap.co', 'Mailtrap Test'))
        ->to(new Address('nyekotrevor1@gmail.com'))
        ->subject('You are awesome!')
        ->category('Integration Test')
        ->text('Congrats for sending test email with Mailtrap!')
    ;

    $response = MailtrapClient::initSendingEmails(
        apiKey: env('MAILTRAP_API_KEY'),
    )->send($email);

})->purpose('Send Mail');