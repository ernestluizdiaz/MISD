<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubmitTicket;

// Register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Show login page
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
  ->name('dashboard');
Route::post('/updateTicketStatus/{ticketId}', [DashboardController::class, 'updateTicketStatus'])->name('updateTicketStatus');


// Table Route
Route::get('/table', [SubmitTicket::class, 'showTickets'])->name('ticket.table');
Route::post('/updateTicketStatus/{ticketId}', [SubmitTicket::class, 'updateTicketStatus'])->name('updateTicketStatus');


// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// Ticket Route
Route::get('/', function () {
  return view('tickets'); // Updated to reference the correct view
});
// Route::get('/submitTicket', [SubmitTicket::class, 'submitTicket'])->name('submit.ticket');
// Route to handle form submission (POST request)
Route::post('/ticketSubmitted', [SubmitTicket::class, 'submitTicket'])->name('submit.ticket');

// Search route
Route::get('/search', function () {
  return view('search');
});
Route::post('/search', [SubmitTicket::class, 'trackTicket'])->name('trackTicket');



// Test Firebase connection
Route::get('/test-firebase', [FirebaseController::class, 'testConnection']);
