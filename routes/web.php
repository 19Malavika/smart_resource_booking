<?php

use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Employee Leave Management System
|--------------------------------------------------------------------------
*/

// Root & Legacy Route Redirects to Portal
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});
Route::get('/home', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

Route::fallback(function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Central Role-Based Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Users (Profiles)
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    // Resources & Categories (Auth, Managed via Policies)
    Route::resource('resource-categories', \App\Http\Controllers\ResourceCategoryController::class)->except(['show']);
    Route::resource('resources', \App\Http\Controllers\ResourceController::class);

    // Bookings
    Route::resource('bookings', \App\Http\Controllers\BookingController::class);
    Route::post('bookings/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/calendar', [\App\Http\Controllers\BookingController::class, 'calendar'])->name('calendar');

    // Notifications
    Route::post('/notifications/{id}/mark-as-read', function($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    })->name('notifications.mark-as-read');
    
    Route::post('/notifications/mark-all-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.mark-all-read');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Employee Management
        Route::resource('employees', EmployeeController::class);

        // Department Management
        Route::resource('departments', DepartmentController::class)->except(['create', 'show', 'edit']);
    });
});

require __DIR__.'/auth.php';
