<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Report $report) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'NEMA eCRMS — Report Received',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.report-submitted',
            with: ['report' => $this->report],
        );
    }
}
