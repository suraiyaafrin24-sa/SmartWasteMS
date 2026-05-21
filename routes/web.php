<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SslCommerzPaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    if ($request->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($request->user()->isWasteCollector()) {
        return redirect()->route('waste_collector.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'users'])->name('users');
    Route::get('/complaints', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'complaints'])->name('complaints');
    Route::post('/complaints/{id}/resolve', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'resolveComplaint'])->name('complaints.resolve');
    Route::get('/reports', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'reports'])->name('reports');
    Route::post('/reports/generate', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'generateReport'])->name('reports.generate');
    Route::get('/reports/download', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'downloadReport'])->name('reports.download');
    Route::get('/reports/download-pdf', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'downloadPdfReport'])->name('reports.pdf.download');
    Route::get('/users/create', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'createUser'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'deleteUser'])->name('users.delete');
    Route::get('/collections', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'collections'])->name('collections');
    Route::get('/duty-schedule', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'dutySchedule'])->name('duty_schedule');

    Route::get('/system-settings', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'settings'])->name('settings');
    Route::get('/profile', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile/avatar', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::get('/duty-roster', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'showDutyRoster'])->name('duty_roster');
    Route::post('/duty-roster', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'storeDutyRoster'])->name('duty_roster.store');
    Route::delete('/duty-roster/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'deleteDutyRoster'])->name('duty_roster.delete');

    Route::post('/requests/{id}/assign', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'assignTask'])->name('requests.assign');
    Route::get('/payments', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'payments'])->name('payments');
    Route::get('/leave-requests', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'leaveRequests'])->name('leave_requests');
    Route::post('/leave-requests/{leaveRequest}/approve', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'approveLeaveRequest'])->name('leave_requests.approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'rejectLeaveRequest'])->name('leave_requests.reject');
});

// Waste Collector Routes
Route::middleware(['auth', 'verified', 'role:waste_collector'])->prefix('waste_collector')->name('waste_collector.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\WasteCollector\WasteCollectorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [\App\Http\Controllers\WasteCollector\WasteCollectorSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\WasteCollector\WasteCollectorSettingsController::class, 'updateProfile'])->name('settings.update');
    Route::delete('/settings/avatar', [\App\Http\Controllers\WasteCollector\WasteCollectorSettingsController::class, 'deleteAvatar'])->name('settings.avatar.delete');
    Route::put('/settings/password', [\App\Http\Controllers\WasteCollector\WasteCollectorSettingsController::class, 'updatePassword'])->name('password.update');
    Route::post('/requests/{id}/accept', [\App\Http\Controllers\WasteCollector\WasteCollectorDashboardController::class, 'acceptRequest'])->name('requests.accept');
    Route::post('/requests/{id}/complete', [\App\Http\Controllers\WasteCollector\WasteCollectorDashboardController::class, 'completeRequest'])->name('requests.complete');
    Route::get('/history', [\App\Http\Controllers\WasteCollector\WasteCollectorDashboardController::class, 'history'])->name('history');

    Route::get('/duty-schedule', [\App\Http\Controllers\WasteCollector\WasteCollectorDashboardController::class, 'dutySchedule'])->name('duty_schedule');
    Route::get('/payment-collection', [\App\Http\Controllers\WasteCollector\WasteCollectorDashboardController::class, 'paymentCollection'])->name('payment_collection');
    Route::get('/leave-requests', [\App\Http\Controllers\WasteCollector\WasteCollectorDashboardController::class, 'leaveRequests'])->name('leave_requests');
    Route::post('/leave-requests', [\App\Http\Controllers\WasteCollector\WasteCollectorDashboardController::class, 'storeLeaveRequest'])->name('leave_requests.store');
});

// User Routes
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/subscription', [\App\Http\Controllers\User\UserDashboardController::class, 'subscription'])->name('subscription');
    Route::get('/my-subscription', [\App\Http\Controllers\User\UserDashboardController::class, 'mySubscription'])->name('my_subscription');
    Route::get('/subscription-receipt', [\App\Http\Controllers\User\UserDashboardController::class, 'subscriptionReceipt'])->name('subscription_receipt');
    Route::get('/settings', [\App\Http\Controllers\User\UserSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\User\UserSettingsController::class, 'updateProfile'])->name('settings.update');
    Route::delete('/settings/avatar', [\App\Http\Controllers\User\UserSettingsController::class, 'deleteAvatar'])->name('settings.avatar.delete');
    Route::put('/settings/password', [\App\Http\Controllers\User\UserSettingsController::class, 'updatePassword'])->name('password.update');

    // User Reports
    Route::get('/reports', [\App\Http\Controllers\User\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [\App\Http\Controllers\User\ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [\App\Http\Controllers\User\ReportController::class, 'store'])->name('reports.store');

    Route::get('/requests/create', [\App\Http\Controllers\User\UserDashboardController::class, 'createRequest'])->name('requests.create');
    Route::get('/get-collector', [\App\Http\Controllers\User\UserDashboardController::class, 'getCollector'])->name('get_collector');
    Route::post('/requests', [\App\Http\Controllers\User\UserDashboardController::class, 'storeRequest'])->name('requests.store');
    Route::get('/history', [\App\Http\Controllers\User\UserDashboardController::class, 'history'])->name('history');
    Route::post('/requests/{request}/cancel', [\App\Http\Controllers\User\UserDashboardController::class, 'cancelRequest'])->name('requests.cancel');
    Route::get('/duty-schedule', [\App\Http\Controllers\User\UserDashboardController::class, 'dutySchedule'])->name('duty_schedule');
    Route::get('/receipt/{request}', [\App\Http\Controllers\User\UserDashboardController::class, 'receipt'])->name('receipt');
});

Route::middleware('auth')->group(function () {
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::match(['get', 'post'], '/pay', [SslCommerzPaymentController::class, 'index'])->name('sslc.pay');
});

Route::post('/success', [SslCommerzPaymentController::class, 'success'])->name('sslc.success');
Route::post('/fail', [SslCommerzPaymentController::class, 'fail'])->name('sslc.failure');
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel'])->name('sslc.cancel');
Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn'])->name('sslc.ipn');

require __DIR__ . '/auth.php';
