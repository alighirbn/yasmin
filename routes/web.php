<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Mail\MyTestEmail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return redirect('login');
});
Route::group(['middleware' => 'checkStatus'], function () {

    //Clear Cache facade value:
    Route::get('/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('optimize');
        Artisan::call('route:cache');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:cache');
        return '<h1>Cache cleared</h1>';
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    //map routes
    require __DIR__ . '/map.php';

    //model_history routes
    require __DIR__ . '/model_history.php';

    //report routes
    require __DIR__ . '/report.php';

    //building routes
    require __DIR__ . '/building.php';

    //customer routes
    require __DIR__ . '/customer.php';

    //service routes
    require __DIR__ . '/service.php';

    //payment routes
    require __DIR__ . '/payment.php';

    //expense routes
    require __DIR__ . '/expense.php';

    //income routes
    require __DIR__ . '/income.php';

    //cash_account routes
    require __DIR__ . '/cash_account.php';

    //cash_transfer routes
    require __DIR__ . '/cash_transfer.php';

    //contract routes
    require __DIR__ . '/contract.php';

    //transfer routes
    require __DIR__ . '/transfer.php';

    //hr routes
    require __DIR__ . '/hr.php';

    //user routes
    require __DIR__ . '/user.php';

    //role routes
    require __DIR__ . '/role.php';

    //notification routes
    require __DIR__ . '/notification.php';

    //profile routes
    require __DIR__ . '/profile.php';
});
//auth routes
require __DIR__ . '/auth.php';
