<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$controller = app(App\Http\Controllers\PublicController::class);
$request = new Illuminate\Http\Request([
    'donor_name' => 'Test Donor',
    'donor_contact' => '09999999999',
    'donor_email' => 'test@example.com',
    'amount' => 300,
    'payment_method' => 'gcash',
    'notes' => 'debug',
]);

$controller->storeDonation($request);
