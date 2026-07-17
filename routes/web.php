<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiningTableController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RestaurantOrderController;
use App\Http\Controllers\StaffShiftController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'public.home')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware(['auth', 'active.user'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('can:manage-restaurant')->group(function () {
        Route::resource('tables', DiningTableController::class)->except(['show']);
        Route::resource('menu-categories', MenuCategoryController::class)->except(['show']);
        Route::resource('menu-items', MenuItemController::class)->except(['show']);
        Route::resource('shifts', StaffShiftController::class)->except(['show']);
    });

    Route::middleware('can:manage-reservations')->group(function () {
        Route::resource('reservations', ReservationController::class)->except(['show']);
    });

    Route::middleware('can:manage-orders')->group(function () {
        Route::resource('orders', RestaurantOrderController::class)->only(['index', 'create', 'store', 'show']);
        Route::patch('orders/{order}/status', [RestaurantOrderController::class, 'updateStatus'])->name('orders.status');
        Route::patch('orders/{order}/pay', [RestaurantOrderController::class, 'closeBill'])->name('orders.pay');
    });

    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->middleware('can:manage-users')->name('activity.index');
});
