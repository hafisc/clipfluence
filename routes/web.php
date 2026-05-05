<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Brand\DashboardController;

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

/*
|--------------------------------------------------------------------------
| Brand Routes (dummy dulu)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('brand')
    ->name('brand.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/campaigns', function () {
            return view('brand.campaigns.index');
        })->name('campaigns');

        Route::get('/campaigns/create', function () {
            return view('brand.campaigns.create');
        })->name('campaigns.create');

        Route::post('/campaigns', function () {
            // dummy
        })->name('campaigns.store');

        Route::get('/submissions', function () {
            return view('brand.submissions.index');
        })->name('submissions');

        Route::get('/finance', function () {
            return view('brand.finance.index');
        })->name('finance');

        Route::get('/payments', function () {
            return view('brand.payments.index');
        })->name('payments');

        Route::get('/settings', function () {
            return view('brand.settings.index');
        })->name('settings');
    });

/*
|--------------------------------------------------------------------------
| Kreator Routes (dummy dulu)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('kreator')
    ->name('kreator.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('kreator.dashboard.index');
        })->name('dashboard');
    });