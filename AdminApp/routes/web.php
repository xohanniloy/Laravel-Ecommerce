<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SSLCommerzCredentialController;

Route::get('/dashboard', [DashboardController::class, 'dashboardPage'])->name('dashboard');

// Route::get('/settings', [SSLCommerzCredentialController::class, 'settingsPage'])->name('settings');
Route::resource('/settings', SSLCommerzCredentialController::class);