<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SSLCommerzCredentialController;

Route::get('/dashboard', [DashboardController::class, 'dashboardPage'])->name('dashboard');

// Route::get('/settings', [SSLCommerzCredentialController::class, 'settingsPage'])->name('settings');
Route::resource('/settings', SSLCommerzCredentialController::class);

// Show the login form
Route::get('/login', [UserController::class, 'loginPage'])->name('login');
// Handle login submission
Route::post('/login', [UserController::class, 'userLogin'])->name('login.attempt');