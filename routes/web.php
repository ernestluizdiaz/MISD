<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

// Register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Show login page
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/', function () {
  return view('tickets');
});


// Test Firebase connection
Route::get('/test-firebase', [FirebaseController::class, 'testConnection']);
