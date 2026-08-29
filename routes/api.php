<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiDonationController;
use App\Http\Controllers\Api\ApiEvacuationController;
use App\Http\Controllers\Api\ApiInventoryController;
use App\Http\Controllers\Api\ApiNotificationController;
use App\Http\Controllers\Api\ApiReliefController;
use App\Http\Controllers\Api\ApiStatsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DonationPaymentController;

Route::prefix('v1')->group(function () {

    // Public donation tracker — must be defined BEFORE the auth group
    // and BEFORE /donations/{id} so Laravel doesn't confuse the path
    Route::get('/donations/track/{code}', [ApiDonationController::class, 'track']);
    Route::post('/webhooks/paymongo', [DonationPaymentController::class, 'webhook'])->name('paymongo.webhook');

    // ── Public read-only data endpoints (for external integrations) ──
    Route::get('/public/inventory', [ApiInventoryController::class, 'publicIndex']);
    Route::get('/public/inventory/{id}', [ApiInventoryController::class, 'publicShow']);
    Route::get('/public/donations', [ApiDonationController::class, 'publicIndex']);
    Route::get('/public/donations/{id}', [ApiDonationController::class, 'publicShow']);
    Route::get('/public/evacuation-centers', [ApiEvacuationController::class, 'publicIndex']);
    Route::get('/public/evacuation-centers/{id}', [ApiEvacuationController::class, 'publicShow']);

    // ── Authentication ─────────────────────────────────────
    Route::post('/auth/login',  [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // ── Protected (JWT required) ─────────────────────────────
    Route::middleware('api.auth')->group(function () {

        // Auth
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Stats endpoint — Group 1 calls this
        Route::get('/stats', [ApiStatsController::class, 'index']);

        // ── Inventory ─────────────────────────────────────────
        Route::get('/inventory',      [ApiInventoryController::class, 'index']);
        Route::get('/inventory/{id}', [ApiInventoryController::class, 'show']);

        // ── Donations ─────────────────────────────────────────
        Route::get('/donations',               [ApiDonationController::class, 'index']);
        Route::post('/donations',              [ApiDonationController::class, 'store']);
        Route::get('/donations/{id}',          [ApiDonationController::class, 'show']);
        Route::patch('/donations/{id}/status', [ApiDonationController::class, 'updateStatus']);

        // ── Evacuation ────────────────────────────────────────
        Route::get('/evacuation-centers',               [ApiEvacuationController::class, 'index']);
        Route::get('/evacuation-centers/{id}',          [ApiEvacuationController::class, 'show']);
        Route::get('/evacuation-centers/{id}/evacuees', [ApiEvacuationController::class, 'evacuees']);
        Route::patch('/evacuation-centers/{id}/status', [ApiEvacuationController::class, 'updateStatus']);

        // ── Relief ────────────────────────────────────────────
        Route::get('/relief/operations',              [ApiReliefController::class, 'index']);
        Route::post('/relief/operations',             [ApiReliefController::class, 'store']);
        Route::get('/relief/operations/{id}',         [ApiReliefController::class, 'show']);
        Route::get('/relief/operations/{id}/report',  [ApiReliefController::class, 'report']);

        // ── Notifications ─────────────────────────────────────
        Route::get('/notifications',            [ApiNotificationController::class, 'index']);
        Route::patch('/notifications/{id}/read', [ApiNotificationController::class, 'markRead']);

    });

});
