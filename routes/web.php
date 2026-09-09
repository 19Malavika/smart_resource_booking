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
    return view('welcome');
});
Route::get('/home', function () {
    return view('welcome');
})->name('home');

Route::get('/sitemap.xml', function () {
    $pages = [
        url('/'),
        url('/login'),
        url('/register'),
    ];
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($pages as $page) {
        $xml .= '<url><loc>' . $page . '</loc></url>';
    }
    $xml .= '</urlset>';
    return response($xml)->header('Content-Type', 'text/xml');
});

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

    // Admin routes are now in routes/admin.php
    
    // Resource Utilization (Admins & Managers)
    Route::get('/utilization', [\App\Http\Controllers\ResourceUtilizationController::class, 'index'])->name('utilization.index');
    Route::get('/utilization/{resource}', [\App\Http\Controllers\ResourceUtilizationController::class, 'show'])->name('utilization.show');

    // Users (Profiles)
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    Route::resource('resources', \App\Http\Controllers\ResourceController::class);

    // Bookings
    Route::resource('bookings', \App\Http\Controllers\BookingController::class);
    Route::post('bookings/{booking}/approve', [\App\Http\Controllers\BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('bookings/{booking}/reject', [\App\Http\Controllers\BookingController::class, 'reject'])->name('bookings.reject');
    Route::post('bookings/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/calendar', [\App\Http\Controllers\BookingController::class, 'calendar'])->name('calendar');
    Route::get('/bookings-calendar', [\App\Http\Controllers\BookingController::class, 'calendar'])->name('bookings.calendar');

    // Resource Maintenance & Availability
    Route::post('resources/{resource}/maintenances', [\App\Http\Controllers\ResourceMaintenanceController::class, 'store'])->name('maintenances.store');
    Route::patch('maintenances/{maintenance}', [\App\Http\Controllers\ResourceMaintenanceController::class, 'update'])->name('maintenances.update');
    Route::delete('maintenances/{maintenance}', [\App\Http\Controllers\ResourceMaintenanceController::class, 'destroy'])->name('maintenances.destroy');
    Route::put('resources/{resource}/availability', [\App\Http\Controllers\ResourceAvailabilityController::class, 'update'])->name('resources.availability.update');

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

});

require __DIR__.'/auth.php';
require __DIR__.'/test.php';
