<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing.index');
});

/*
|--------------------------------------------------------------------------
| Guest Routes (belum login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

/*
|--------------------------------------------------------------------------
| Logout Route (harus login)
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes (dummy dulu)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard.index');
        })->name('dashboard');

        Route::get('/ugc', function () {
            return view('admin.ugc.index');
        })->name('ugc');

        Route::get('/kyc', function () {
            return view('admin.kyc.index');
        })->name('kyc');

        Route::get('/withdrawals', function () {
            return view('admin.withdrawals.index');
        })->name('withdrawals');

        Route::get('/campaigns', function () {
            return view('admin.campaigns.index');
        })->name('campaigns');

        Route::get('/logs', function () {
            return view('admin.logs.index');
        })->name('logs');
    });