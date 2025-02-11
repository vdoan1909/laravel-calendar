<?php

namespace App\Jobs;

use App\Mail\ScheduleReminderEmail;
use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendScheduleReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $schedule;
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function handle(): void
    {
        Mail::to($this->schedule->lecturer->email)->send(new ScheduleReminderEmail($this->schedule));

        foreach ($this->schedule->students as $student) {
            Mail::to($student->email)->send(new ScheduleReminderEmail($this->schedule));
        }
    }
}
