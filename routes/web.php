<?php

use App\Http\Controllers\CaseController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\SupportDashboardController;
use App\Http\Controllers\Admin\SubscriptionAdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Admin\LawFirmAdminController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', fn() => view('landing'))->name('landing');

// Admin support site (separate from tenant app)
Route::middleware(['auth', 'verified', 'admin.only'])->group(function () {
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportDashboardController::class, 'index'])->name('dashboard');
        Route::post('/firm-context', [SupportDashboardController::class, 'setFirmContext'])->name('firm-context');
    });

    Route::prefix('admin/subscriptions')->name('admin.subscriptions.')->group(function () {
        Route::get('/', [SubscriptionAdminController::class, 'index'])->name('index');
        Route::get('/enterprise', [SubscriptionAdminController::class, 'enterprise'])->name('enterprise');
        Route::post('/', [SubscriptionAdminController::class, 'store'])->name('store');
        Route::patch('{subscription}/status', [SubscriptionAdminController::class, 'updateSubscriptionStatus'])->name('status');
        Route::patch('{subscription}/enterprise', [SubscriptionAdminController::class, 'updateEnterprise'])->name('enterprise.update');
        Route::patch('payments/{payment}/status', [SubscriptionAdminController::class, 'updatePaymentStatus'])->name('payments.status');
    });

    Route::prefix('admin/law-firms')->name('admin.law-firms.')->group(function () {
        Route::get('/', [LawFirmAdminController::class, 'index'])->name('index');
        Route::post('/', [LawFirmAdminController::class, 'store'])->name('store');
        Route::post('/owners', [LawFirmAdminController::class, 'storeOwner'])->name('owners.store');
    });
});

// Tenant app (non-admin users)
Route::middleware(['auth', 'verified', 'non_admin', 'subscription.access'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Access locked
    Route::get('/access-locked', [SubscriptionController::class, 'locked'])->name('access.locked');

    // Global Search
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    // Cases
    Route::resource('cases', CaseController::class);
    Route::get('cases-scan', fn() => view('cases.scan'))->name('cases.scan');

    // Clients
    Route::resource('clients', ClientController::class);

    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

    // Sessions
    Route::resource('sessions', SessionController::class);

    // Team
    Route::resource('team', TeamController::class);
    Route::post('team/{team}/resend-invitation', [TeamController::class, 'resendInvitation'])->name('team.resend-invitation');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/mark-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-read');

    // Audit Logs
    Route::get('logs', [AuditLogController::class, 'index'])->name('logs.index');

    // Subscription
    Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription');
    Route::post('subscription/renew', [SubscriptionController::class, 'renew'])->name('subscription.renew');
    Route::post('subscription/request-plan', [SubscriptionController::class, 'requestPlan'])->name('subscription.request-plan');

    // Settings — split into two separate pages
    Route::get('settings',                fn() => redirect()->route('settings.profile'))->name('settings');
    Route::get('settings/profile',        [SettingsController::class, 'showProfile'])    ->name('settings.profile');
    Route::post('settings/profile',       [SettingsController::class, 'updateProfile'])  ->name('settings.profile.update');
    Route::delete('settings/avatar',      [SettingsController::class, 'removeAvatar'])   ->name('settings.avatar.remove');
    Route::get('settings/firm',           [SettingsController::class, 'showFirm'])       ->name('settings.firm');
    Route::post('settings/firm',          [SettingsController::class, 'updateFirm'])     ->name('settings.firm.update');
    Route::delete('settings/firm/logo',   [SettingsController::class, 'removeFirmLogo']) ->name('settings.firm.logo.remove');

});