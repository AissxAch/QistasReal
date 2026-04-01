<?php

use App\Console\Commands\EnforceEnterpriseSubscriptionLifecycle;
use App\Console\Commands\PurgeExpiredTeamInvitations;
use App\Console\Commands\SendEnterpriseContractReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(SendEnterpriseContractReminders::class)->dailyAt('08:00');
Schedule::command(EnforceEnterpriseSubscriptionLifecycle::class)->dailyAt('08:30');
Schedule::command(PurgeExpiredTeamInvitations::class)->hourly();
