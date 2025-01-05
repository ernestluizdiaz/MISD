<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubmitTicket;
use App\Http\Controllers\ResolveTicketController;

// Register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Show login page
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth'])->group(function () {
  // Dashboard
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::post('/updateTicketStatus/{ticketId}', [DashboardController::class, 'updateTicketStatus'])->name('updateTicketStatus');

  // Route to handle form submission (POST request)
  Route::post('/submitTicket', [SubmitTicket::class, 'submitTicket'])->name('ticket.submit');

  // Route to show all tickets at table
  Route::get('/table', [SubmitTicket::class, 'showTickets'])->name('ticket.table');

  // Handle ticket status update from submitTicket
  Route::post('/submitTicket/updateTicketStatus/{ticketId}', [SubmitTicket::class, 'updateTicketStatus'])->name('ticket.updateStatus');

  // Logout
  Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// Home route
Route::get('/', function () {
  return view('tickets'); // Updated to reference the correct view
});

// Test Firebase connection
Route::get('/test-firebase', [FirebaseController::class, 'testConnection']);
