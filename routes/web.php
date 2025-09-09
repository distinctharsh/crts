<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MastersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\UsageReportController;


// Redirect root URL to /home
Route::redirect('/', '/home');

// Show welcome view at /home
Route::get('/home', function () {
    // Get public IP (check for proxy headers first)
    $publicIp = request()->header('X-Forwarded-For') ?? request()->ip();
    // If X-Forwarded-For has multiple IPs, take the first one
    if (strpos($publicIp, ',') !== false) {
        $publicIp = trim(explode(',', $publicIp)[0]);
    }
    return view('welcome', ['user_ip' => $publicIp]);
})->name('home');


// Live complaints dashboard (TV/room display)
Route::get('/complaints/live', [ComplaintController::class, 'live'])->name('complaints.live');
// Live complaints JSON data endpoint for polling
Route::get('/complaints/live-data', [ComplaintController::class, 'liveData'])->name('complaints.liveData');


// Public ticket routes
Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');

// History route is public but protected by IP
Route::get('/complaints/history', [ComplaintController::class, 'history'])
    ->name('complaints.history')
    ->middleware(\App\Http\Middleware\CheckIPAccess::class);


Route::get('/complaints/history', [ComplaintController::class, 'history'])
    ->name('complaints.history')
    ->middleware(\App\Http\Middleware\CheckIPAccess::class);

Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth', 'force.password.change'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    // Complaint routes
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/data', [ComplaintController::class, 'data'])->name('complaints.data');
    Route::get('/complaints/{complaint}/edit', [ComplaintController::class, 'edit'])->name('complaints.edit');
    Route::put('/complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');
    Route::post('/complaints/{complaint}/assign', [ComplaintController::class, 'assign'])->name('complaints.assign');
    Route::post('/complaints/{complaint}/resolve', [ComplaintController::class, 'resolve'])->name('complaints.resolve');
    Route::post('/complaints/{complaint}/revert', [ComplaintController::class, 'revert'])->name('complaints.revert');
    Route::post('/complaints/{complaint}/comment', [ComplaintController::class, 'comment'])->name('complaints.comment');
    // API routes for dynamic content
    Route::get('/api/assignable-users', [ComplaintController::class, 'getAssignableUsers'])->name('api.assignable-users');
    Route::resource('users', UserController::class);
    Route::post('/users/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
});

Route::get('/api/complaints/lookup', [App\Http\Controllers\ComplaintController::class, 'lookup'])->name('api.complaints.lookup');
Route::get('/complaints/track', [App\Http\Controllers\ComplaintController::class, 'track'])->name('complaints.track');

// Redirect any GET or POST /register access to /home
Route::match(['get', 'post'], '/register', function () {
    return redirect('/home');
});

// Handle GET /logout gracefully to avoid 419 error
Route::get('/logout', function () {
    if (auth()->check()) {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/home')->with('success', 'You have been logged out successfully.');
    }
    return redirect('/home');
});


Route::middleware(['auth', 'can:isManager'])->group(function () {

    Route::get('/masters', [MastersController::class, 'index'])->name('masters.index');
    // Network Types
    Route::post('/masters/network-types', [MastersController::class, 'storeNetworkType'])->name('masters.network-types.store');
    Route::put('/masters/network-types/{networkType}', [MastersController::class, 'updateNetworkType'])->name('masters.network-types.update');
    Route::delete('/masters/network-types/{networkType}', [MastersController::class, 'destroyNetworkType'])->name('masters.network-types.destroy');
    // Sections
    Route::post('/masters/sections', [MastersController::class, 'storeSection'])->name('masters.sections.store');
    Route::put('/masters/sections/{section}', [MastersController::class, 'updateSection'])->name('masters.sections.update');
    Route::delete('/masters/sections/{section}', [MastersController::class, 'destroySection'])->name('masters.sections.destroy');
    // Statuses
    Route::post('/masters/statuses', [MastersController::class, 'storeStatus'])->name('masters.statuses.store');
    Route::put('/masters/statuses/{status}', [MastersController::class, 'updateStatus'])->name('masters.statuses.update');
    Route::delete('/masters/statuses/{status}', [MastersController::class, 'destroyStatus'])->name('masters.statuses.destroy');
    // Verticals
    Route::post('/masters/verticals', [MastersController::class, 'storeVertical'])->name('masters.verticals.store');
    Route::put('/masters/verticals/{vertical}', [MastersController::class, 'updateVertical'])->name('masters.verticals.update');
    Route::delete('/masters/verticals/{vertical}', [MastersController::class, 'destroyVertical'])->name('masters.verticals.destroy');
    Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');

    Route::get('/usage-report', [UsageReportController::class, 'index'])->name('usage-report.index');
});


require __DIR__ . '/auth.php';

// Force override GET /login to always redirect to /home (for Laravel 12+)
Route::get('/login', function () {
    return redirect('/home');
})->name('login-override');

// Fallback route for all unknown URLs
Route::fallback(function () {
    return redirect('/home')->with('error', 'You were redirected because the page was not found. आप जिस पेज पर गए हैं, वह मौजूद नहीं है।');
});
