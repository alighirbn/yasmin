<?php
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'notification'], function() {
    //index
    Route::get('/',[NotificationController::class,'index'])->middleware(['auth','verified'])->name('notification.index');
    //read notification
    Route::get('/markasread/{id}',[NotificationController::class,'markasread'])->middleware(['auth','verified'])->name('notification.markasread');
    //MarkAllAsRead notification
    Route::get('/markallasread',[NotificationController::class,'markallasread'])->middleware(['auth','verified'])->name('notification.markallasread');
});
