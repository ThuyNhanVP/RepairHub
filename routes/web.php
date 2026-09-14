<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\RepairJobController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::resource('customers', CustomerController::class);
    Route::resource('receptions', ReceptionController::class);
    
    Route::resource('repair-jobs', RepairJobController::class)->except(['create', 'store']);
    Route::post('repair-jobs/{repair_job}/add-step', [RepairJobController::class, 'addStep'])->name('repair-jobs.add-step');
});
