<?php

use App\Models\Employee;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Matches the sidebar's existing 'hrm.index' link from the theme package.
    Route::get('/hrm', fn () => view('hrm.index'))->name('hrm.index');

    Route::get('/departments', fn () => view('departments.index'))->name('departments.index');

    Route::get('/employees', fn () => view('employees.index'))->name('employees.index');
    Route::get('/employees/create', fn () => view('employees.create'))->name('employees.create');
    Route::get('/employees/{employee}/edit', fn (Employee $employee) => view('employees.edit', compact('employee')))->name('employees.edit');

    Route::get('/attendance', fn () => view('attendance.index'))->name('attendance.index');
    Route::get('/holidays', fn () => view('holidays.index'))->name('holidays.index');
    Route::get('/payroll', fn () => view('payroll.index'))->name('payroll.index');
});
