<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SSLCommerzCredentialController;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'dashboardPage'])->name('dashboard');

// Route::get('/settings', [SSLCommerzCredentialController::class, 'settingsPage'])->name('settings');
Route::resource('/settings', SSLCommerzCredentialController::class);
Route::resource('/brands', BrandController::class);
Route::resource('/categories', CategoryController::class);
Route::resource('/sliders', SliderController::class);
Route::resource('/products', ProductController::class);

// Show the login form
Route::get('/login', [UserController::class, 'loginPage'])->name('login');
// Handle login submission
Route::post('/login', [UserController::class, 'userLogin'])->name('login.attempt');