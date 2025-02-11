<?php

namespace App\Console\Commands;

use App\Jobs\SendScheduleReminderJob;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ScheduleReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi email nhắc nhở lịch sắp tới';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $schedules = Schedule::whereDate('start', $tomorrow)->get();

        foreach ($schedules as $schedule) {
            SendScheduleReminderJob::dispatch($schedule);
        }
    }
}
