<?php

use App\Http\Controllers\Customer\Auth\CustomerLoginController;
use App\Http\Controllers\Customer\Auth\CustomerRegisterController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\OrderItemController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->name('customer.')->group(function () {
    
    /** GUEST ROUTES **/
    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [CustomerLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [CustomerLoginController::class, 'login'])->name('login.post');
        
        Route::get('/register', [CustomerRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [CustomerRegisterController::class, 'register'])->name('register.post');
    });
    
    /** AUTH ROUTES **/
    Route::middleware('auth:customer')->group(function () {
        Route::post('/logout', [CustomerLoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        /**
         * DATATABLE ROUTES
         **/
        Route::group([
            'prefix' => 'datatable',
            'as' => 'datatable.'
        ], function () {
            Route::get('/orders', [OrderController::class, 'dataTable'])->name('orders');
            Route::get('/order-items', [OrderItemController::class, 'dataTable'])->name('order-items');
        });
        
        Route::resource('/orders', OrderController::class)->names('orders')->only(['index', 'show']);
    });
});
