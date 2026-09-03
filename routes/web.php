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
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReliefController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;

// ── Public routes ──────────────────────────────
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public donation tracker (no login needed)
Route::get('/track', [DonationController::class, 'track'])->name('donations.track');

// Require authentication from the site root; public services remain available below.
Route::get('/', [LoginController::class, 'showLogin'])->name('home');
Route::get('/public-home', [PublicController::class, 'home'])->name('public.home');
Route::get('/evac-centers', [PublicController::class, 'evacCenters'])->name('public.evac_centers');
Route::get('/evac-centers/map-data', [PublicController::class, 'evacCenterMapData'])->name('public.evac_centers.map_data');
Route::post('/evac-centers/register-family', [PublicController::class, 'registerFamily'])->name('public.evac_centers.register_family');
Route::post('/evac-centers/check-in-family', [PublicController::class, 'checkInFamily'])->name('public.evac_centers.check_in_family');
Route::get('/captcha/image', [PublicController::class, 'captcha'])->name('public.captcha');
Route::get('/captcha', [PublicController::class, 'captcha']);
Route::get('/api/evac/nearest', [PublicController::class, 'nearestEvac'])->name('public.evac_centers.nearest');
Route::get('/evac-centers/{evacuationCenter}', [PublicController::class, 'evacCenter'])->name('public.evac_center');
Route::get('/donate', [PublicController::class, 'donate'])->name('donate');
Route::post('/donate', [PublicController::class, 'storeDonation'])->name('donate.submit');
Route::get('/donate/success/{donation}', [PublicController::class, 'paymentSuccess'])->name('public.payment.success');

// ── Authenticated routes ────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Inventory - view
    Route::middleware('role:super_admin,mdrrmo,lgu_staff,evac_manager,evacuation_manager')
        ->group(function () {
          Route::get('/inventory', [InventoryController::class, 'index'])
              ->name('inventory.index');
        });

    // Inventory - add/edit
    Route::middleware('role:super_admin,mdrrmo,lgu_staff')
        ->group(function () {
          Route::get('/inventory/create', [InventoryController::class, 'create'])
              ->name('inventory.create');
          Route::post('/inventory', [InventoryController::class, 'store'])
              ->name('inventory.store');
          Route::get('/inventory/{inventoryItem}/edit', [InventoryController::class, 'edit'])
              ->name('inventory.edit');
          Route::put('/inventory/{inventoryItem}', [InventoryController::class, 'update'])
              ->name('inventory.update');
        });

        // Keep the static create path before the dynamic {inventoryItem} path.
        Route::middleware('role:super_admin,mdrrmo,lgu_staff')
                ->group(function () {
                    Route::get('/inventory/{inventoryItem}', [InventoryController::class, 'show'])
                            ->name('inventory.show');
                });

    // Inventory - delete
    Route::middleware('role:super_admin')
        ->group(function () {
          Route::delete('/inventory/{inventoryItem}', [InventoryController::class, 'destroy'])
              ->name('inventory.destroy');
        });

    // Donations - view
    Route::middleware('role:super_admin,mdrrmo,lgu_staff')
        ->group(function () {
          Route::get('/donations', [DonationController::class, 'index'])
              ->name('donations.index');
        });

    // Donations - add/edit
        Route::middleware('role:super_admin,mdrrmo')
        ->group(function () {
          Route::get('/donations/create', [DonationController::class, 'create'])
              ->name('donations.create');
          Route::post('/donations', [DonationController::class, 'store'])
              ->name('donations.store');
          Route::get('/donations/{donation}/edit', [DonationController::class, 'edit'])
              ->name('donations.edit');
          Route::put('/donations/{donation}', [DonationController::class, 'update'])
              ->name('donations.update');
        });

        // Keep the static create path before the dynamic {donation} path.
        Route::middleware('role:super_admin,mdrrmo,lgu_staff')
                ->group(function () {
                    Route::get('/donations/{donation}', [DonationController::class, 'show'])
                            ->name('donations.show');
                });

    // Donations - delete
    Route::middleware('role:super_admin')
        ->group(function () {
          Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])
              ->name('donations.destroy');
        });

    // Evacuation - view
    Route::middleware('role:super_admin,mdrrmo,evac_manager')
        ->group(function () {
          Route::get('/evacuation', [EvacuationController::class, 'index'])
              ->name('evacuation.index');
          Route::get('/evacuation/{evacuation}/evacuees', [EvacuationController::class, 'evacuees'])
              ->name('evacuation.evacuees');
          Route::get('/evacuation/{evacuation}', [EvacuationController::class, 'show'])
              ->name('evacuation.show');
        });

    // Evacuation - manage
    Route::middleware('role:super_admin,mdrrmo,evac_manager')
        ->group(function () {
          Route::get('/evacuation/create', [EvacuationController::class, 'create'])
              ->name('evacuation.create');
          Route::post('/evacuation', [EvacuationController::class, 'store'])
              ->name('evacuation.store');
          Route::get('/evacuation/{evacuation}/edit', [EvacuationController::class, 'edit'])
              ->name('evacuation.edit');
          Route::put('/evacuation/{evacuation}', [EvacuationController::class, 'update'])
              ->name('evacuation.update');
          Route::post('/evacuation/{evacuation}/checkin',
             [EvacuationController::class, 'checkin'])
             ->name('evacuation.checkin');
          Route::patch('/evacuation/{evacuation}/checkout/{evacuee}',
             [EvacuationController::class, 'checkout'])
             ->name('evacuation.checkout');
        });

    // Evacuation - delete
    Route::middleware('role:super_admin')
        ->group(function () {
          Route::delete('/evacuation/{evacuation}', [EvacuationController::class, 'destroy'])
              ->name('evacuation.destroy');
        });

    // Relief - view
    Route::middleware('role:super_admin,mdrrmo,lgu_staff,evac_manager,evacuation_manager')
        ->group(function () {
          Route::get('/relief', [ReliefController::class, 'index'])
              ->name('relief.index');
        });

    // Relief - manage
    Route::middleware('role:super_admin,mdrrmo')
        ->group(function () {
          Route::get('/relief/create', [ReliefController::class, 'create'])
              ->name('relief.create');
          Route::post('/relief', [ReliefController::class, 'store'])
              ->name('relief.store');
          Route::get('/relief/{relief}/edit', [ReliefController::class, 'edit'])
              ->name('relief.edit');
          Route::put('/relief/{relief}', [ReliefController::class, 'update'])
              ->name('relief.update');
          Route::post('/relief/{relief}/distribute',
             [ReliefController::class, 'distribute'])
             ->name('relief.distribute');
        });

    // Keep the static create path before the dynamic {relief} path.
    Route::middleware('role:super_admin,mdrrmo,lgu_staff,evac_manager,evacuation_manager')
        ->group(function () {
          Route::get('/relief/{relief}', [ReliefController::class, 'show'])
              ->name('relief.show');
        });

    // Relief - delete
    Route::middleware('role:super_admin')
        ->group(function () {
          Route::delete('/relief/{relief}', [ReliefController::class, 'destroy'])
              ->name('relief.destroy');
        });

    // Reports available to warehouse staff
        Route::middleware('role:super_admin,mdrrmo,lgu_staff,evac_manager,evacuation_manager')
        ->group(function () {
          Route::get('/reports', [ReportController::class, 'index'])
              ->name('reports.index');
          Route::get('/reports/inventory/print',  [ReportController::class, 'inventoryPrint'])
              ->name('reports.inventory.print');
          Route::get('/reports/inventory/excel',  [ReportController::class, 'exportInventoryExcel'])
              ->name('reports.inventory.excel');
          Route::get('/reports/inventory/pdf',  [ReportController::class, 'exportInventoryPdf'])
              ->name('reports.inventory.pdf');
        });

    // Restricted reports
    Route::middleware('role:super_admin,mdrrmo')
        ->group(function () {
          Route::get('/reports/donations/print',  [ReportController::class, 'donationsPrint'])
              ->name('reports.donations.print');
          Route::get('/reports/evacuation/print', [ReportController::class, 'evacuationPrint'])
              ->name('reports.evacuation.print');
          Route::get('/reports/relief/print',     [ReportController::class, 'reliefPrint'])
              ->name('reports.relief.print');
          Route::get('/reports/donations/excel',  [ReportController::class, 'exportDonationsExcel'])
              ->name('reports.donations.excel');
          Route::get('/reports/evacuation/excel', [ReportController::class, 'exportEvacuationExcel'])
              ->name('reports.evacuation.excel');
          Route::get('/reports/relief/excel',     [ReportController::class, 'exportReliefExcel'])
              ->name('reports.relief.excel');
          Route::get('/reports/donations/pdf',  [ReportController::class, 'exportDonationsPdf'])
              ->name('reports.donations.pdf');
          Route::get('/reports/evacuation/pdf', [ReportController::class, 'exportEvacuationPdf'])
              ->name('reports.evacuation.pdf');
          Route::get('/reports/relief/pdf',     [ReportController::class, 'exportReliefPdf'])
              ->name('reports.relief.pdf');
        });

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::get('/notifications/json', [NotificationController::class, 'json'])
        ->name('notifications.json');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])
        ->name('notifications.markAllRead');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markRead'])
        ->name('notifications.read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
        ->name('notifications.destroy');

    // Audit Trail
    Route::middleware('role:super_admin,mdrrmo')
        ->group(function () {
          Route::get('/audit', [AuditLogController::class, 'index'])
              ->name('audit.index');
          Route::get('/audit/{auditLog}', [AuditLogController::class, 'show'])
              ->name('audit.show');
        });

    // Donor portal
    Route::middleware('role:donor')
        ->group(function () {
          Route::get('/my-donations', [DonorPortalController::class, 'index'])
              ->name('donor.index');
        });

    // Payment (allow donor to access pay routes as well)
    Route::middleware('role:super_admin,mdrrmo,donor')
        ->group(function () {
          Route::get('/donations/{donation}/pay', [DonationPaymentController::class, 'create'])
              ->name('donations.payment.create');
          Route::post('/donations/{donation}/pay', [DonationPaymentController::class, 'checkout'])
              ->name('donations.payment.checkout');
          Route::get('/donations/{donation}/pay/success', [DonationPaymentController::class, 'success'])
              ->name('donations.payment.success');
          Route::get('/donations/{donation}/pay/cancel', [DonationPaymentController::class, 'cancel'])
              ->name('donations.payment.cancel');
        });

    Route::middleware('role:super_admin,mdrrmo')
        ->group(function () {
          Route::get('/payments/history', [DonationPaymentController::class, 'history'])
              ->name('donations.payment.history');
        });

});

