<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\IncentiveController;
use App\Http\Controllers\AdvanceController;
use App\Http\Controllers\TerminationController;
use App\Http\Controllers\SOAController;

Route::get('hr/payrolls/current-month', [PayrollController::class, 'currentMonth'])->name('hr.payrolls.currentMonth');
Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    Route::resource('employees', EmployeeController::class);
    Route::resource('payrolls', PayrollController::class);
    Route::resource('incentives', IncentiveController::class);
    Route::resource('advances', AdvanceController::class);
    Route::resource('terminations', TerminationController::class);
    Route::get('soa', [SOAController::class, 'index'])->name('soa.index');
});
