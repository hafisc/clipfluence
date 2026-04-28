<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('landing.index');
});

// LOGIN
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// REGISTER
// Route::get('/register', [RegisterController::class, 'showRegisterForm']);
// Route::post('/register', [RegisterController::class, 'register']);

// DASHBOARD
Route::get('/dashboard', function () {
    return view('admin.dashboard.index');
});