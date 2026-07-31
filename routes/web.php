<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationPaymentController;
use App\Http\Controllers\DonorPortalController;
use App\Http\Controllers\EvacuationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReliefController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;

// ── Public routes ──────────────────────────────
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public donation tracker (no login needed)
Route::get('/track', [DonationController::class, 'track'])->name('donations.track');

// ── Authenticated routes ────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:super_admin,drrm_officer,warehouse_staff'])->group(function () {
        Route::resource('inventory', InventoryController::class);
    });

    Route::middleware(['role:super_admin,drrm_officer'])->group(function () {
        Route::resource('donations', DonationController::class);
        Route::resource('relief', ReliefController::class);
        Route::post('/relief/{relief}/distribute', [ReliefController::class, 'distribute'])
            ->name('relief.distribute');

        Route::get('/donations/{donation}/pay', [DonationPaymentController::class, 'create'])
            ->name('donations.payment.create');
        Route::post('/donations/{donation}/pay', [DonationPaymentController::class, 'checkout'])
            ->name('donations.payment.checkout');
        Route::get('/donations/{donation}/pay/success', [DonationPaymentController::class, 'success'])
            ->name('donations.payment.success');
        Route::get('/donations/{donation}/pay/cancel', [DonationPaymentController::class, 'cancel'])
            ->name('donations.payment.cancel');
        Route::get('/payments/history', [DonationPaymentController::class, 'history'])
            ->name('donations.payment.history');
    });

    Route::middleware(['role:super_admin,drrm_officer,warehouse_staff'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        Route::get('/reports/inventory/print',  [ReportController::class, 'inventoryPrint'])
            ->name('reports.inventory.print');
        Route::get('/reports/donations/print',  [ReportController::class, 'donationsPrint'])
            ->name('reports.donations.print');
        Route::get('/reports/evacuation/print', [ReportController::class, 'evacuationPrint'])
            ->name('reports.evacuation.print');
        Route::get('/reports/relief/print',     [ReportController::class, 'reliefPrint'])
            ->name('reports.relief.print');

        Route::get('/reports/inventory/excel',  [ReportController::class, 'exportInventoryExcel'])
            ->name('reports.inventory.excel');
        Route::get('/reports/donations/excel',  [ReportController::class, 'exportDonationsExcel'])
            ->name('reports.donations.excel');
        Route::get('/reports/evacuation/excel', [ReportController::class, 'exportEvacuationExcel'])
            ->name('reports.evacuation.excel');
        Route::get('/reports/relief/excel',     [ReportController::class, 'exportReliefExcel'])
            ->name('reports.relief.excel');

        Route::get('/reports/inventory/pdf',  [ReportController::class, 'exportInventoryPdf'])
            ->name('reports.inventory.pdf');
        Route::get('/reports/donations/pdf',  [ReportController::class, 'exportDonationsPdf'])
            ->name('reports.donations.pdf');
        Route::get('/reports/evacuation/pdf', [ReportController::class, 'exportEvacuationPdf'])
            ->name('reports.evacuation.pdf');
        Route::get('/reports/relief/pdf',     [ReportController::class, 'exportReliefPdf'])
            ->name('reports.relief.pdf');
    });

    Route::middleware(['role:super_admin,drrm_officer,evacuation_manager'])->group(function () {
        Route::resource('evacuation', EvacuationController::class);
        Route::post('/evacuation/{evacuation}/checkin', [EvacuationController::class, 'checkin'])
            ->name('evacuation.checkin');
        Route::patch('/evacuation/{evacuation}/checkout/{evacuee}', [EvacuationController::class, 'checkout'])
            ->name('evacuation.checkout');
    });

    Route::middleware(['role:super_admin,drrm_officer,warehouse_staff,evacuation_manager'])->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])
            ->name('notifications.markAllRead');
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markRead'])
            ->name('notifications.read');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
            ->name('notifications.destroy');
    });

    Route::middleware(['role:super_admin,drrm_officer'])->group(function () {
        Route::get('/audit', [AuditLogController::class, 'index'])
            ->name('audit.index');
        Route::get('/audit/{auditLog}', [AuditLogController::class, 'show'])
            ->name('audit.show');
    });

    Route::middleware(['role:donor'])->group(function () {
        Route::get('/donor', [DonorPortalController::class, 'index'])->name('donor.index');
    });

});

