
<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'report'], function () {


    //category
    Route::get('/category', [ReportController::class, 'category'])->middleware(['auth', 'verified', 'permission:report-category'])->name('report.category');

    //due_installments
    Route::get('/due_installments', [ReportController::class, 'due_installments'])->middleware(['auth', 'verified', 'permission:report-due_installments'])->name('report.due_installments');

    //first_installment
    Route::get('/first_installment', [ReportController::class, 'first_installment'])->middleware(['auth', 'verified', 'permission:report-first_installment'])->name('report.first_installment');

    //general_report
    Route::get('/general_report', [ReportController::class, 'general_report'])->middleware(['auth', 'verified', 'permission:report-general_report'])->name('report.general_report');
});
