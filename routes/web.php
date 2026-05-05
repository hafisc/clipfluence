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

         Route::get('/dashboard', fn() => view('admin.dashboard.index'))->name('dashboard');
        Route::get('/users', fn() => view('admin.users.index'))->name('users');
        Route::get('/kreators', fn() => view('admin.kreators.index'))->name('kreators');
        Route::get('/brands', fn() => view('admin.brands.index'))->name('brands');
        Route::get('/kyc', fn() => view('admin.kyc.index'))->name('kyc');
        Route::get('/campaigns', fn() => view('admin.campaigns.index'))->name('campaigns');
        Route::get('/ugc', fn() => view('admin.ugc.index'))->name('ugc');
        Route::get('/payouts', fn() => view('admin.payouts.index'))->name('payouts');
        Route::get('/withdrawals', fn() => view('admin.withdrawals.index'))->name('withdrawals');
        Route::get('/disputes', fn() => view('admin.disputes.index'))->name('disputes');
        Route::get('/analytics', fn() => view('admin.analytics.index'))->name('analytics');
        Route::get('/fraud', fn() => view('admin.fraud.index'))->name('fraud');
        Route::get('/notifications', fn() => view('admin.notifications.index'))->name('notifications');
        Route::get('/logs', fn() => view('admin.logs.index'))->name('logs');
        Route::get('/settings', fn() => view('admin.settings.index'))->name('settings');
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
