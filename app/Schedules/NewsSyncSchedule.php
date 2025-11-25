<?php

declare(strict_types=1);

namespace App\Schedules;

use App\Jobs\News\SyncNewsJob;
use Illuminate\Console\Scheduling\Schedule;

class NewsSyncSchedule
{
    /**
     * @param Schedule $schedule
     *
     * @return void
     */
    public static function register(Schedule $schedule): void
    {
        $schedule->job(new SyncNewsJob())
            ->daily()
            ->withoutOverlapping()
            ->onOneServer()
            ->name('news:sync');
    }
}
