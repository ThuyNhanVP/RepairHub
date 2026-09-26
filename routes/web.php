<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\RepairJobController;
use App\Http\Controllers\WarrantyClaimController;
use App\Http\Controllers\WarrantyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::resource('customers', CustomerController::class);
    Route::resource('receptions', ReceptionController::class);

    Route::resource('repair-jobs', RepairJobController::class)->except(['create', 'store']);
    Route::post('repair-jobs/{repair_job}/add-step', [RepairJobController::class, 'addStep'])->name('repair-jobs.add-step');

    Route::resource('warranties', WarrantyController::class);
    Route::resource('warranty-claims', WarrantyClaimController::class);

    Route::resource('parts', PartController::class);
    Route::post('parts/{part}/add-stock', [PartController::class, 'addStock'])->name('parts.add-stock');
    Route::post('parts/{part}/remove-stock', [PartController::class, 'removeStock'])->name('parts.remove-stock');
    Route::post('parts/{part}/adjust-stock', [PartController::class, 'adjustStock'])->name('parts.adjust-stock');
});
