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

    /* ========= USER TRANSACTIONS (INI YANG PENTING) ========= */
    Route::get('/my-transactions', [TransactionController::class, 'userIndex'])
        ->name('user.transactions.index');

    Route::get('/my-transactions/create', [TransactionController::class, 'userCreate'])
        ->name('user.transactions.create');

    Route::post('/my-transactions', [TransactionController::class, 'userStore'])
        ->name('user.transactions.store');

    /* ================= ADMIN ================= */
    Route::middleware('admin')->group(function () {

        Route::resource('transactions', TransactionController::class);
        Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])
            ->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])
            ->name('transactions.reject');

        Route::resource('categories', WasteCategoryController::class);
        Route::resource('users', UserController::class);

        Route::get('/reports/admin', [ReportController::class, 'adminReport'])
            ->name('reports.admin');
    });
    // USER
    Route::middleware(['auth'])->group(function () {
        Route::get('/withdrawals', [WithdrawalController::class,'index'])->name('withdrawals.user.index');
        Route::get('/withdrawals/create', [WithdrawalController::class,'create'])->name('withdrawals.user.create');
        Route::post('/withdrawals', [WithdrawalController::class,'store'])->name('withdrawals.user.store');
    });

    Route::get('/withdrawals/{id}', [WithdrawalController::class, 'show'])
    ->name('withdrawals.user.show')
    ->middleware('auth');

    // ADMIN
    Route::middleware(['auth','admin'])->group(function () {
        Route::get('/withdrawals', [WithdrawalAdminController::class, 'index'])
        ->name('withdrawals.admin.index');

        Route::get('/withdrawals/{withdrawal}', [WithdrawalAdminController::class, 'show'])
            ->name('withdrawals.admin.show');

        Route::post('/withdrawals/{withdrawal}/approve', [WithdrawalAdminController::class, 'approve'])
            ->name('withdrawals.admin.approve');

        Route::post('/withdrawals/{withdrawal}/reject', [WithdrawalAdminController::class, 'reject'])
            ->name('withdrawals.admin.reject');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/redeem', [RedeemController::class, 'index'])->name('redeem.index');
        Route::post('/redeem/{id}', [RedeemController::class, 'store'])->name('redeem.store');
    });

    Route::middleware(['auth'])->group(function () {
        Route::post('/points/redeem', [PointController::class, 'redeem'])
            ->name('points.redeem');
    });

});
