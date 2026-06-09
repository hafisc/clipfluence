<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AIChatController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\FinanceController as AdminFinanceController;
use App\Http\Controllers\Admin\KycController as AdminKycController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing.index');
});

// AI Chatbox (accessible from all pages)
Route::post('/ai/chat', [AIChatController::class, 'chat'])->name('ai.chat');

Route::get('/contact', function () {
    return view('landing.contact');
})->name('contact');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);
    return back()->with('success', 'Pesan Anda telah berhasil dikirim! Tim support kami akan menghubungi Anda segera.');
})->name('contact.send');

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
        Route::get('/users', [UserController::class, 'internal'])->name('users');
        Route::post('/users', [UserController::class, 'storeInternal'])->name('users.store');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/kreators', [UserController::class, 'kreators'])->name('kreators');
        Route::get('/brands', [UserController::class, 'brands'])->name('brands');
        Route::get('/kyc', [AdminKycController::class, 'index'])->name('kyc');
        Route::patch('/kyc/{user}', [AdminKycController::class, 'update'])->name('kyc.update');
        Route::get('/campaigns', [AdminCampaignController::class, 'index'])->name('campaigns');
        Route::post('/campaigns', [AdminCampaignController::class, 'store'])->name('campaigns.store');
        Route::patch('/campaigns/{campaign}', [AdminCampaignController::class, 'update'])->name('campaigns.update');
        Route::delete('/campaigns/{campaign}', [AdminCampaignController::class, 'destroy'])->name('campaigns.destroy');
        Route::get('/ugc', fn() => view('admin.ugc.index'))->name('ugc');
        Route::get('/payouts', [AdminFinanceController::class, 'payouts'])->name('payouts');
        Route::patch('/payouts/{deposit}', [AdminFinanceController::class, 'updateDeposit'])->name('payouts.update');
        Route::get('/withdrawals', [AdminFinanceController::class, 'withdrawals'])->name('withdrawals');
        Route::patch('/withdrawals/{withdrawal}', [AdminFinanceController::class, 'updateWithdrawal'])->name('withdrawals.update');
        Route::get('/disputes', fn() => view('admin.disputes.index'))->name('disputes');
        Route::get('/analytics', fn() => view('admin.analytics.index'))->name('analytics');
        Route::get('/fraud', fn() => view('admin.fraud.index'))->name('fraud');
        Route::get('/notifications', fn() => view('admin.notifications.index'))->name('notifications');
        Route::get('/logs', fn() => view('admin.logs.index'))->name('logs');
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
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
    Route::get('/campaigns/{campaign}/edit', [\App\Http\Controllers\Brand\CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::patch('/campaigns/{campaign}', [\App\Http\Controllers\Brand\CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [\App\Http\Controllers\Brand\CampaignController::class, 'destroy'])->name('campaigns.destroy');
    
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
