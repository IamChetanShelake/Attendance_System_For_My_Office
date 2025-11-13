<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HolidaysController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('admin.employees');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('admin.attendance');
    Route::get('/rules', [RulesController::class, 'index'])->name('admin.rules');
    Route::get('/leave', [LeaveController::class, 'index'])->name('admin.leave');
    Route::get('/holidays', [HolidaysController::class, 'index'])->name('admin.holidays');
});
