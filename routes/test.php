<?php
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/test-employee-dashboard', function () {
    $employee = User::where('role', 'employee')->first();
    Auth::login($employee);
    return app()->call([\App\Http\Controllers\DashboardController::class, 'index']);
});
Route::get('/test-manager-dashboard', function () {
    $manager = User::where('role', 'manager')->first();
    Auth::login($manager);
    return app()->call([\App\Http\Controllers\DashboardController::class, 'index']);
});
Route::get('/test-admin-dashboard', function () {
    $admin = User::where('role', 'admin')->first();
    Auth::login($admin);
    return app()->call([\App\Http\Controllers\DashboardController::class, 'index']);
});
