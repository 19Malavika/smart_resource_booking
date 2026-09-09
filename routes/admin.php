<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard'); // Assuming dashboard is handled by existing controller
})->name('dashboard.fallback');

Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
Route::resource('categories', \App\Http\Controllers\Admin\ResourceCategoryController::class);
Route::resource('resources', \App\Http\Controllers\Admin\ResourceController::class);
Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class);
Route::resource('maintenance', \App\Http\Controllers\Admin\MaintenanceController::class);
Route::resource('reports', \App\Http\Controllers\Admin\ReportController::class);
Route::resource('audit-logs', \App\Http\Controllers\Admin\AuditLogController::class);
