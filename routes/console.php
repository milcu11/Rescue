<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\NotificationService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('notifications:check-expirations', function (NotificationService $notifications) {
    $created = $notifications->createExpiringItemAlerts();
    $this->info("Created {$created} expiration alert(s).");
})->purpose('Create in-app alerts for inventory items nearing expiration');
