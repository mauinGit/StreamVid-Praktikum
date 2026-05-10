<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MyListController;
use App\Http\Controllers\CollectionController;
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
Route::view('/terms-and-privacy', 'terms')->name('terms');

Route::get('/setup-database', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return 'Database setup successful! <a href="/">Go to Home</a>';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/debug-storage', function () {
    $symlink = public_path('storage');
    $target  = storage_path('app/public');

    return response()->json([
        'symlink_exists'    => is_link($symlink),
        'symlink_target'    => is_link($symlink) ? readlink($symlink) : 'TIDAK ADA',
        'target_dir_exists' => is_dir($target),
        'files_in_storage'  => is_dir($target) ? scandir($target) : 'FOLDER KOSONG/TIDAK ADA',
        'app_url'           => config('app.url'),
    ]);
});

Route::get('/force-symlink', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');
    
    // Hapus apapun yang ada di public/storage (file, folder, symlink usang)
    if (file_exists($link) || is_link($link)) {
        if (is_dir($link) && !is_link($link)) {
            \Illuminate\Support\Facades\File::deleteDirectory($link);
        } else {
            unlink($link);
        }
    }
    
    // Buat target direktori jika belum ada
    if (!file_exists($target)) {
        mkdir($target, 0775, true);
    }
    
    // Buat symlink langsung dengan PHP
    $success = symlink($target, $link);
    
    return response()->json([
        'success' => $success,
        'message' => $success ? 'Symlink berhasil dibuat!' : 'Gagal membuat symlink.',
        'target' => $target,
        'link' => $link
    ]);
});

Route::get('/do-symlink', function () {
    $output = [];
    
    // 1. Cek isi folder public
    $output['ls_public_before'] = shell_exec('ls -la /app/public');
    
    // 2. Coba hapus
    $output['rm_result'] = shell_exec('rm -rf /app/public/storage 2>&1');
    
    // 3. Coba buat symlink
    $output['ln_result'] = shell_exec('ln -sfn /app/storage/app/public /app/public/storage 2>&1');
    
    // 4. Cek isi folder public setelahnya
    $output['ls_public_after'] = shell_exec('ls -la /app/public');
    
    // 5. Cek isi storage target
    $output['ls_target'] = shell_exec('ls -la /app/storage/app/public');
    
    return '<pre>' . print_r($output, true) . '</pre>';
});

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
    Route::get('/payment/receipt', [PaymentController::class, 'receipt'])->name('payment.receipt');
    Route::get('/payment/history', [PaymentController::class, 'history'])->name('payment.history');

    // Koleksi Saya (replaces My List + History)
    Route::get('/koleksi', [CollectionController::class, 'index'])->name('collection.index');

    // My List toggle (keep for add/remove functionality)
    Route::get('/my-list', [CollectionController::class, 'index'])->name('mylist.index');
    Route::post('/my-list/toggle/{film}', [MyListController::class, 'toggle'])->name('mylist.toggle');


    // Watch History (alias to collection)
    Route::get('/history', [CollectionController::class, 'index'])->name('history.index');

    // Watch (requires active subscription)
    Route::get('/watch/{film}', [WatchController::class, 'play'])
        ->middleware('subscription')
        ->name('films.watch');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('/profile/cancel-subscription', [ProfileController::class, 'cancelSubscription'])->name('profile.cancel-subscription');
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

    // Subscription / Transaction Management
    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/{payment}/approve', [AdminSubscriptionController::class, 'approve'])->name('subscriptions.approve');
    Route::post('/subscriptions/{payment}/reject', [AdminSubscriptionController::class, 'reject'])->name('subscriptions.reject');

    // Film featured toggle
    Route::post('/films/{film}/toggle-featured', [AdminFilmController::class, 'toggleFeatured'])->name('films.toggle-featured');
    Route::get('/films-search-json', [AdminFilmController::class, 'searchJson'])->name('films.search-json');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
});

require __DIR__.'/auth.php';
