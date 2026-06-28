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
    public function notifyReportSubmitted(Report $report): bool
    {
        $report->loadMissing(['crime', 'stuff']);

        $email = $this->resolveEmail($report);

        if (! $email) {
            return false;
        }

        $trackingCode = $report->tracking_code ?? 'N/A';
        $logMessage   = "NEMA eCRMS: Your report was received. Tracking code: {$trackingCode}.";

        return $this->sendEmail($report, $email, new ReportSubmitted($report), $logMessage);
    }

    public function notifyStatusUpdated(Report $report): void
    {
        $report->loadMissing(['crime', 'stuff']);

        $email = $this->resolveEmail($report);

        if (! $email) {
            return;
        }

        $trackingCode = $report->tracking_code ?? (string) $report->report_id;
        $logMessage   = "NEMA eCRMS: Report {$trackingCode} status is now {$report->status}.";

        $this->sendEmail($report, $email, new ReportStatusUpdated($report), $logMessage);
    }

    private function resolveEmail(Report $report): ?string
    {
        return $report->stuff?->email;
    }

    private function sendEmail(Report $report, string $email, object $mailable, string $logMessage): bool
    {
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

    private function logNotification(Report $report, string $channel, string $message, string $status): void
    {
        Notification::create([
            'report_id'       => $report->report_id,
            'stuff_id'        => $report->stuff_id,
            'message'         => $message,
            'sent_at'         => now(),
            'channel'         => $channel,
            'delivery_status' => $status,
        ]);
    }
}
