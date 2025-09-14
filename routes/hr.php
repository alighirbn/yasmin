<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\IncentiveController;
use App\Http\Controllers\AdvanceController;
use App\Http\Controllers\TerminationController;
use App\Http\Controllers\SOAController;

Route::get('/payrolls/generate-all', [PayrollController::class, 'generateAll'])->name('hr.payrolls.generateAll');

Route::get('hr/payrolls/current-month', [PayrollController::class, 'currentMonth'])->name('hr.payrolls.currentMonth');

Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {

    // Employee Resource
    Route::resource('employees', EmployeeController::class);

    // Scanner routes for employees
    Route::get('employees/{employee}/scanner', [EmployeeController::class, 'scancreate'])->name('employees.scan.create');
    Route::post('employees/{employee}/scanner', [EmployeeController::class, 'scanstore'])->name('employees.scan.store');

    // Archive routes for employees
    Route::get('employees/{employee}/archive', [EmployeeController::class, 'archivecreate'])->name('employees.archive.create');
    Route::post('employees/{employee}/archive', [EmployeeController::class, 'archivestore'])->name('employees.archive.store');
    Route::get('employees/{employee}/archive/show', [EmployeeController::class, 'archiveshow'])->name('employees.archive.show');

    // Other resources
    Route::resource('payrolls', PayrollController::class);
    Route::resource('incentives', IncentiveController::class);
    Route::resource('advances', AdvanceController::class);
    Route::resource('terminations', TerminationController::class);

    // SOA
    Route::get('soa', [SOAController::class, 'index'])->name('soa.index');
});
