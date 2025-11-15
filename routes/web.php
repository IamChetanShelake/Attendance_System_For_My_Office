<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\AccessoriesController;
use App\Http\Controllers\AccountabilityController;
use App\Http\Controllers\AttendanceManagementController;
use App\Http\Controllers\CeoController;
use App\Http\Controllers\DailyAttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeLoginController;
use App\Http\Controllers\LeaveManagementController;
use App\Http\Controllers\OfficeRulesController;
use App\Http\Controllers\ReportsController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Employee Routes
    Route::get('/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('admin.employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');
    Route::post('/employees/update-probation', [EmployeeController::class, 'updateProbation'])->name('admin.employees.update-probation');

    // Employee Login Routes
    Route::get('/employee-logins', [EmployeeLoginController::class, 'index'])->name('admin.employee-logins.index');
    Route::get('/employee-logins/create', [EmployeeLoginController::class, 'create'])->name('admin.employee-logins.create');
    Route::post('/employee-logins', [EmployeeLoginController::class, 'store'])->name('admin.employee-logins.store');
    Route::get('/employee-logins/{login}', [EmployeeLoginController::class, 'show'])->name('admin.employee-logins.show');
    Route::get('/employee-logins/{login}/edit', [EmployeeLoginController::class, 'edit'])->name('admin.employee-logins.edit');
    Route::put('/employee-logins/{login}', [EmployeeLoginController::class, 'update'])->name('admin.employee-logins.update');
    Route::delete('/employee-logins/{login}', [EmployeeLoginController::class, 'destroy'])->name('admin.employee-logins.destroy');
    Route::post('/employee-logins/{login}/toggle-status', [EmployeeLoginController::class, 'toggleStatus'])->name('admin.employee-logins.toggle-status');
    Route::post('/employee-logins/{login}/reset-password', [EmployeeLoginController::class, 'resetPassword'])->name('admin.employee-logins.reset-password');
    Route::post('/employee-logins/generate/{employeeId}', [EmployeeLoginController::class, 'generateForEmployee'])->name('admin.employee-logins.generate-for-employee');

    Route::get('/daily-attendance', [DailyAttendanceController::class, 'index'])->name('admin.daily-attendance');
    Route::post('/daily-attendance/generate-qr', [DailyAttendanceController::class, 'generateQrCode'])->name('admin.daily-attendance.generate-qr');
    Route::post('/daily-attendance/verify-qr', [DailyAttendanceController::class, 'verifyQrCode'])->name('admin.daily-attendance.verify-qr');
    Route::get('/daily-attendance/status-check/{qrIdentifier}', [DailyAttendanceController::class, 'checkAttendanceStatus'])->name('admin.daily-attendance.status-check');

    // Attendance Management Routes
    Route::get('/manage-attendance', [AttendanceManagementController::class, 'index'])->name('admin.manage-attendance');
    Route::get('/manual-attendance', [AttendanceManagementController::class, 'recordForm'])->name('admin.manual-attendance');
    Route::post('/manage-attendance/record', [AttendanceManagementController::class, 'recordManualAttendance'])->name('admin.manage-attendance.record');
    Route::get('/manage-attendance/employee-pin/{employeeId}', [AttendanceManagementController::class, 'getEmployeePinStatus'])->name('admin.manage-attendance.employee-pin');
    Route::get('/manage-attendance/employee-today/{employeeId}', [AttendanceManagementController::class, 'getEmployeeTodayAttendance'])->name('admin.manage-attendance.employee-today');
    Route::resource('accountability', AccountabilityController::class, ['as' => 'admin']);
    Route::resource('documents', DocumentsController::class, ['as' => 'admin']);
    Route::get('/documents/employee/{employee}', [DocumentsController::class, 'employeeDocuments'])->name('admin.documents.employee');
    Route::get('/documents/{document}/download', [DocumentsController::class, 'download'])->name('admin.documents.download');
    Route::resource('accessories', AccessoriesController::class, ['as' => 'admin']);
    Route::resource('office-rules', OfficeRulesController::class, ['as' => 'admin']);
    Route::post('/office-rules/{rule}/toggle-status', [OfficeRulesController::class, 'toggleStatus'])->name('admin.office-rules.toggle-status');
    Route::post('/office-rules/create-default-rules', [OfficeRulesController::class, 'createDefaultRules'])->name('admin.office-rules.create-default');
    Route::post('/office-rules/preview', [OfficeRulesController::class, 'preview'])->name('admin.office-rules.preview');
    Route::resource('employee-leave', LeaveManagementController::class, ['as' => 'admin', 'parameters' => ['employee-leave' => 'leave']]);
    Route::post('/employee-leave/{leave}/approve', [LeaveManagementController::class, 'approve'])->name('admin.employee-leave.approve');
    Route::post('/employee-leave/{leave}/reject', [LeaveManagementController::class, 'reject'])->name('admin.employee-leave.reject');
    Route::post('/employee-leave/bulk-action', [LeaveManagementController::class, 'bulkAction'])->name('admin.employee-leave.bulk-action');
    Route::resource('academic-calendar', AcademicCalendarController::class, ['as' => 'admin']);
    Route::get('/academic-calendar/events/data', [AcademicCalendarController::class, 'getEvents'])->name('admin.academic-calendar.events.data');
    Route::get('/report-management', [ReportsController::class, 'index'])->name('admin.report-management');
    Route::get('/employee-accessories', [AccessoriesController::class, 'index'])->name('admin.employee-accessories');
    Route::get('/ceo-login', [CeoController::class, 'index'])->name('admin.ceo-login');
});
