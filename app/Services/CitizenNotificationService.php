<?php

namespace App\Services;

use App\Mail\ReportStatusUpdated;
use App\Mail\ReportSubmitted;
use App\Models\Notification;
use App\Models\Report;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CitizenNotificationService
{
    public function __construct(private SmsService $sms) {}

    /**
     * @return array{sms_sent: bool, email_sent: bool, has_phone: bool, has_email: bool}
     */
    public function notifyReportSubmitted(Report $report): array
    {
        $report->loadMissing(['crime', 'stuff.whistleblowerProfile']);

        $trackingCode = $report->tracking_code ?? 'N/A';
        $trackUrl     = route('report.track');
        $smsMessage   = "NEMA eCRMS: Your report was received. Tracking code: {$trackingCode}. Track status: {$trackUrl}";

        $email = $this->resolveEmail($report);
        $phone = $this->resolvePhone($report);

        $emailSent = $this->sendEmail(
            $report,
            $email,
            new ReportSubmitted($report),
            $smsMessage
        );

        $smsSent = $this->sendSms($report, $phone, $smsMessage);

        return [
            'sms_sent'   => $smsSent,
            'email_sent' => $emailSent,
            'has_phone'  => filled($phone),
            'has_email'  => filled($email),
        ];
    }

    public function notifyStatusUpdated(Report $report): void
    {
        $report->loadMissing(['crime', 'stuff.whistleblowerProfile']);

        $trackingCode = $report->tracking_code ?? (string) $report->report_id;
        $trackUrl     = route('report.track');
        $smsMessage   = "NEMA eCRMS: Report {$trackingCode} status is now {$report->status}. Track: {$trackUrl}";

        $this->sendEmail(
            $report,
            $this->resolveEmail($report),
            new ReportStatusUpdated($report),
            $smsMessage
        );

        $this->sendSms($report, $this->resolvePhone($report), $smsMessage);
    }

    private function resolveEmail(Report $report): ?string
    {
        return $report->stuff?->email;
    }

    private function resolvePhone(Report $report): ?string
    {
        if ($report->stuff) {
            return $report->stuff->whistleblowerProfile?->phone_number;
        }

        return $report->reporter_phone;
    }

    private function sendEmail(Report $report, ?string $email, object $mailable, string $logMessage): bool
    {
        if (! $email) {
            return false;
        }

        try {
            Mail::to($email)->send($mailable);
            $this->logNotification($report, 'email', $logMessage, 'sent');

            return true;
        } catch (\Throwable $e) {
            Log::error('Citizen email notification failed', [
                'report_id' => $report->report_id,
                'email'     => $email,
                'error'     => $e->getMessage(),
            ]);
            $this->logNotification($report, 'email', $logMessage, 'failed');

            return false;
        }
    }

    private function sendSms(Report $report, ?string $phone, string $message): bool
    {
        if (! $phone) {
            return false;
        }

        if (! $this->sms->isConfigured()) {
            Log::warning('SMS skipped: TextBee not configured.', [
                'report_id' => $report->report_id,
            ]);
            $this->logNotification($report, 'SMS', $message, 'failed');

            return false;
        }

        try {
            $this->sms->send($phone, $message);
            $this->logNotification($report, 'SMS', $message, 'sent');

            return true;
        } catch (\Throwable $e) {
            Log::error('Citizen SMS notification failed', [
                'report_id' => $report->report_id,
                'phone'     => $phone,
                'error'     => $e->getMessage(),
            ]);
            $this->logNotification($report, 'SMS', $message, 'failed');

            return false;
        }
    }

    private function logNotification(Report $report, string $channel, string $message, string $status): void
    {
        Notification::create([
            'report_id'        => $report->report_id,
            'stuff_id'         => $report->stuff_id,
            'message'          => $message,
            'sent_at'          => now(),
            'channel'          => $channel,
            'delivery_status'  => $status,
        ]);
    }
}
