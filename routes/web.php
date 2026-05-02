<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MyListController;
use App\Http\Controllers\WatchController;
use App\Http\Controllers\WatchHistoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FilmController as AdminFilmController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/films', [FilmController::class, 'index'])->name('films.index');

// ==========================================
// AUTHENTICATED USER ROUTES
// ==========================================
Route::middleware('auth')->group(function () {
    // Film detail (auth required per PRD)
    Route::get('/films/{film}', [FilmController::class, 'show'])->name('films.show');

    // Subscription
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/checkout', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');

    // Payment
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');

    // My List
    Route::get('/my-list', [MyListController::class, 'index'])->name('mylist.index');
    Route::post('/my-list/toggle/{film}', [MyListController::class, 'toggle'])->name('mylist.toggle');

    // Watch (requires active subscription)
    Route::get('/watch/{film}', [WatchController::class, 'play'])
        ->middleware('subscription')
        ->name('films.watch');

    // Watch History
    Route::get('/history', [WatchHistoryController::class, 'index'])->name('history.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Film CRUD
    Route::resource('films', AdminFilmController::class);

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');

    // Subscription Management
    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
});

require __DIR__.'/auth.php';
