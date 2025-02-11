<?php

namespace App\Mail;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lịch sắp tới',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'schedule-reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
