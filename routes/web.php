<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing.index');
});

// Auth Routes
Route::post('/midtrans/webhook', [\App\Http\Controllers\Brand\FinanceController::class, 'webhook'])->name('midtrans.webhook');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

// Fitur Sign in with Google
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Halaman Onboarding Khusus User Baru dari Google
Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [\App\Http\Controllers\Auth\OnboardingController::class, 'index'])->name('onboarding');
    Route::post('/onboarding', [\App\Http\Controllers\Auth\OnboardingController::class, 'store'])->name('onboarding.store');
});

// Admin Dashboard & Features (Protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware([\App\Http\Middleware\IsAdmin::class])->group(function() {
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
});


// Kreator Dashboard (Protected)
Route::middleware(['auth', \App\Http\Middleware\IsKreator::class])->prefix('kreator')->name('kreator.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Kreator\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/campaigns', [\App\Http\Controllers\Kreator\CampaignController::class, 'index'])->name('campaigns');
    Route::get('/campaigns/search', [\App\Http\Controllers\Kreator\CampaignController::class, 'search'])->name('campaigns.search');
    Route::get('/campaigns/{id}', [\App\Http\Controllers\Kreator\CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaigns/{id}/join', [\App\Http\Controllers\Kreator\CampaignController::class, 'join'])->name('campaigns.join');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Kreator\NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Kreator\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Kreator\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    // AI Tools Routes
    Route::get('/ai-tools', [\App\Http\Controllers\Kreator\AIToolController::class, 'index'])->name('ai_tools');
    Route::post('/ai-tools/video-info', [\App\Http\Controllers\Kreator\AIToolController::class, 'getVideoInfo']);
    Route::post('/ai-tools/generate', [\App\Http\Controllers\Kreator\AIToolController::class, 'generate']);
    Route::post('/ai-tools/clips-status', [\App\Http\Controllers\Kreator\AIToolController::class, 'checkClipsStatus']);
    Route::post('/ai-tools/history', [\App\Http\Controllers\Kreator\AIToolController::class, 'getHistory']);
    Route::delete('/ai-tools/clip/{clip}', [\App\Http\Controllers\Kreator\AIToolController::class, 'deleteClip']);
    Route::get('/ai-tools/test', function() { return response()->json(['success' => true, 'message' => 'Route works!']); });
    
    Route::get('/submissions', [\App\Http\Controllers\Kreator\SubmissionController::class, 'index'])->name('submissions');
    Route::get('/submissions/create', fn() => view('kreator.submissions.create'))->name('submissions.create');
    Route::post('/submissions', [\App\Http\Controllers\Kreator\SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/finance', [\App\Http\Controllers\Kreator\FinanceController::class, 'index'])->name('finance');
    Route::post('/finance/bank', [\App\Http\Controllers\Kreator\FinanceController::class, 'updateBank'])->name('finance.bank.update');
    Route::post('/finance/withdraw', [\App\Http\Controllers\Kreator\FinanceController::class, 'withdraw'])->name('finance.withdraw');
});

// Brand Dashboard (Protected)
Route::middleware(['auth', \App\Http\Middleware\IsBrand::class])->prefix('brand')->name('brand.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Brand\DashboardController::class, 'index'])->name('dashboard');
    
    // Brand Campaigns
    Route::get('/campaigns', [\App\Http\Controllers\Brand\CampaignController::class, 'index'])->name('campaigns');
    Route::get('/campaigns/search', [\App\Http\Controllers\Brand\CampaignController::class, 'search'])->name('campaigns.search');
    Route::get('/campaigns/create', [\App\Http\Controllers\Brand\CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [\App\Http\Controllers\Brand\CampaignController::class, 'store'])->name('campaigns.store');
    
    Route::get('/submissions', [\App\Http\Controllers\Brand\SubmissionController::class, 'index'])->name('submissions');
    Route::post('/submissions/{submission}/approve', [\App\Http\Controllers\Brand\SubmissionController::class, 'approve'])->name('submissions.approve');
    Route::post('/submissions/{submission}/reject', [\App\Http\Controllers\Brand\SubmissionController::class, 'reject'])->name('submissions.reject');
    Route::get('/finance', [\App\Http\Controllers\Brand\FinanceController::class, 'index'])->name('finance');
    Route::post('/finance/topup', [\App\Http\Controllers\Brand\FinanceController::class, 'topup'])->name('finance.topup');
    Route::post('/finance/topup/callback', [\App\Http\Controllers\Brand\FinanceController::class, 'handleCallbackCallback'])->name('finance.topup.callback');
    
    Route::get('/settings', [\App\Http\Controllers\Brand\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [\App\Http\Controllers\Brand\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/avatar', [\App\Http\Controllers\Brand\SettingsController::class, 'updateAvatar'])->name('settings.avatar');
    Route::post('/settings/password', [\App\Http\Controllers\Brand\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/notifications', [\App\Http\Controllers\Brand\SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    
    Route::get('/notifications', [\App\Http\Controllers\Brand\NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Brand\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Brand\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});
