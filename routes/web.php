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

    // User Management
    Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'users'])->name('users');
    Route::get('/users/create', [\App\Http\Controllers\Admin\AdminUserController::class, 'createUser'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\AdminUserController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\AdminUserController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\AdminUserController::class, 'deleteUser'])->name('users.delete');

    // Complaints
    Route::get('/complaints', [\App\Http\Controllers\Admin\AdminComplaintController::class, 'complaints'])->name('complaints');
    Route::post('/complaints/{id}/resolve', [\App\Http\Controllers\Admin\AdminComplaintController::class, 'resolveComplaint'])->name('complaints.resolve');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\AdminReportController::class, 'reports'])->name('reports');
    Route::post('/reports/generate', [\App\Http\Controllers\Admin\AdminReportController::class, 'generateReport'])->name('reports.generate');
    Route::get('/reports/download', [\App\Http\Controllers\Admin\AdminReportController::class, 'downloadReport'])->name('reports.download');
    Route::get('/reports/download-pdf', [\App\Http\Controllers\Admin\AdminReportController::class, 'downloadPdfReport'])->name('reports.pdf.download');

    // Collections
    Route::get('/collections', [\App\Http\Controllers\Admin\AdminCollectionController::class, 'collections'])->name('collections');
    Route::post('/requests/{id}/assign', [\App\Http\Controllers\Admin\AdminCollectionController::class, 'assignTask'])->name('requests.assign');

    // Duty Roster & Schedule
    Route::get('/duty-schedule', [\App\Http\Controllers\Admin\AdminDutyRosterController::class, 'dutySchedule'])->name('duty_schedule');
    Route::get('/duty-roster', [\App\Http\Controllers\Admin\AdminDutyRosterController::class, 'showDutyRoster'])->name('duty_roster');
    Route::post('/duty-roster', [\App\Http\Controllers\Admin\AdminDutyRosterController::class, 'storeDutyRoster'])->name('duty_roster.store');
    Route::delete('/duty-roster/{id}', [\App\Http\Controllers\Admin\AdminDutyRosterController::class, 'deleteDutyRoster'])->name('duty_roster.delete');

    // Settings
    Route::get('/system-settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'settings'])->name('settings');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'profile'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile/avatar', [\App\Http\Controllers\Admin\AdminProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    // Payments
    Route::get('/payments', [\App\Http\Controllers\Admin\AdminPaymentController::class, 'payments'])->name('payments');

    // Leave Requests
    Route::get('/leave-requests', [\App\Http\Controllers\Admin\AdminLeaveRequestController::class, 'leaveRequests'])->name('leave_requests');
    Route::post('/leave-requests/{leaveRequest}/approve', [\App\Http\Controllers\Admin\AdminLeaveRequestController::class, 'approveLeaveRequest'])->name('leave_requests.approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [\App\Http\Controllers\Admin\AdminLeaveRequestController::class, 'rejectLeaveRequest'])->name('leave_requests.reject');
});

// Waste Collector Routes
Route::middleware(['auth', 'verified', 'role:waste_collector'])->prefix('waste_collector')->name('waste_collector.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\WasteCollector\WasteCollectorDashboardController::class, 'index'])->name('dashboard');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\WasteCollector\WasteCollectorSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\WasteCollector\WasteCollectorSettingsController::class, 'updateProfile'])->name('settings.update');
    Route::delete('/settings/avatar', [\App\Http\Controllers\WasteCollector\WasteCollectorSettingsController::class, 'deleteAvatar'])->name('settings.avatar.delete');
    Route::put('/settings/password', [\App\Http\Controllers\WasteCollector\WasteCollectorSettingsController::class, 'updatePassword'])->name('password.update');

    // Requests
    Route::post('/requests/{id}/accept', [\App\Http\Controllers\WasteCollector\WasteCollectorRequestController::class, 'acceptRequest'])->name('requests.accept');
    Route::post('/requests/{id}/complete', [\App\Http\Controllers\WasteCollector\WasteCollectorRequestController::class, 'completeRequest'])->name('requests.complete');
    Route::get('/history', [\App\Http\Controllers\WasteCollector\WasteCollectorRequestController::class, 'history'])->name('history');

    // Duty Schedule
    Route::get('/duty-schedule', [\App\Http\Controllers\WasteCollector\WasteCollectorDutyScheduleController::class, 'dutySchedule'])->name('duty_schedule');

    // Payment Collection
    Route::get('/payment-collection', [\App\Http\Controllers\WasteCollector\WasteCollectorPaymentController::class, 'paymentCollection'])->name('payment_collection');

    // Leave Requests
    Route::get('/leave-requests', [\App\Http\Controllers\WasteCollector\WasteCollectorLeaveRequestController::class, 'leaveRequests'])->name('leave_requests');
    Route::post('/leave-requests', [\App\Http\Controllers\WasteCollector\WasteCollectorLeaveRequestController::class, 'storeLeaveRequest'])->name('leave_requests.store');
});

// User Routes
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\UserDashboardController::class, 'index'])->name('dashboard');

    // Subscription
    Route::get('/subscription', [\App\Http\Controllers\User\UserSubscriptionController::class, 'subscription'])->name('subscription');
    Route::get('/my-subscription', [\App\Http\Controllers\User\UserSubscriptionController::class, 'mySubscription'])->name('my_subscription');
    Route::get('/subscription-receipt', [\App\Http\Controllers\User\UserSubscriptionController::class, 'subscriptionReceipt'])->name('subscription_receipt');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\User\UserSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\User\UserSettingsController::class, 'updateProfile'])->name('settings.update');
    Route::delete('/settings/avatar', [\App\Http\Controllers\User\UserSettingsController::class, 'deleteAvatar'])->name('settings.avatar.delete');
    Route::put('/settings/password', [\App\Http\Controllers\User\UserSettingsController::class, 'updatePassword'])->name('password.update');

    // User Reports
    Route::get('/reports', [\App\Http\Controllers\User\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [\App\Http\Controllers\User\ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [\App\Http\Controllers\User\ReportController::class, 'store'])->name('reports.store');

    // Requests
    Route::get('/requests/create', [\App\Http\Controllers\User\UserRequestController::class, 'createRequest'])->name('requests.create');
    Route::get('/get-collector', [\App\Http\Controllers\User\UserRequestController::class, 'getCollector'])->name('get_collector');
    Route::post('/requests', [\App\Http\Controllers\User\UserRequestController::class, 'storeRequest'])->name('requests.store');
    Route::get('/history', [\App\Http\Controllers\User\UserRequestController::class, 'history'])->name('history');
    Route::post('/requests/{request}/cancel', [\App\Http\Controllers\User\UserRequestController::class, 'cancelRequest'])->name('requests.cancel');
    Route::get('/receipt/{request}', [\App\Http\Controllers\User\UserRequestController::class, 'receipt'])->name('receipt');

    // Duty Schedule
    Route::get('/duty-schedule', [\App\Http\Controllers\User\UserDutyScheduleController::class, 'dutySchedule'])->name('duty_schedule');
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

require __DIR__.'/auth.php';
