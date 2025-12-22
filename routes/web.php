<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WasteCategoryController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\Admin\WithdrawalAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');

/* ================= AUTH ================= */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/* ================= AUTH USER ================= */
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* ========= USER TRANSACTIONS ========= */
    Route::get('/my-transactions', [TransactionController::class, 'userIndex'])
        ->name('user.transactions.index');

    Route::get('/my-transactions/create', [TransactionController::class, 'userCreate'])
        ->name('user.transactions.create');

    Route::post('/my-transactions', [TransactionController::class, 'userStore'])
        ->name('user.transactions.store');

    /* ========= USER WITHDRAWALS ========= */
    Route::get('/my-withdrawals', [WithdrawalController::class, 'index'])
        ->name('withdrawals.user.index');
    
    Route::get('/my-withdrawals/create', [WithdrawalController::class, 'create'])
        ->name('withdrawals.user.create');
    
    Route::post('/my-withdrawals', [WithdrawalController::class, 'store'])
        ->name('withdrawals.user.store');
    
    Route::get('/my-withdrawals/{id}', [WithdrawalController::class, 'show'])
        ->name('withdrawals.user.show');

    /* ========= POINTS REDEMPTION ========= */
    Route::post('/points/redeem', [PointController::class, 'redeem'])
        ->name('points.redeem');

    /* ================= ADMIN ROUTES ================= */
    Route::middleware('admin')->group(function () {

        // Transactions
        Route::resource('transactions', TransactionController::class);
        Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])
            ->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])
            ->name('transactions.reject');

        // Withdrawals (Admin)
        Route::get('/admin/withdrawals', [WithdrawalAdminController::class, 'index'])
            ->name('withdrawals.admin.index');

        Route::get('/admin/withdrawals/{withdrawal}', [WithdrawalAdminController::class, 'show'])
            ->name('withdrawals.admin.show');

        Route::post('/admin/withdrawals/{withdrawal}/approve', [WithdrawalAdminController::class, 'approve'])
            ->name('withdrawals.admin.approve');

        Route::post('/admin/withdrawals/{withdrawal}/reject', [WithdrawalAdminController::class, 'reject'])
            ->name('withdrawals.admin.reject');

        // Categories and Users
        Route::resource('categories', WasteCategoryController::class);
        Route::resource('users', UserController::class);

        // Reports
        Route::get('/reports/admin', [ReportController::class, 'adminReport'])
            ->name('reports.admin');
    });
});
