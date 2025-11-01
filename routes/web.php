<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

// ✅ الصفحة الرئيسية توجه لصفحة تسجيل الدخول
Route::get('/', function () {
    return redirect()->route('login');
});

// ✅ جميع الـ routes المحمية بوسيط checkStatus
Route::group(['middleware' => 'checkStatus'], function () {

    // Clear Cache + Run npm dev + Redirect to dashboard
    Route::get('/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');

        // ✅ شغل npm run dev فقط بالبيئة المحلية
        if (app()->environment('local')) {
            exec('npm run dev > /dev/null 2>&1 &');
        }

        // ✅ رجع للـ dashboard
        return redirect()->route('dashboard')->with('status', 'Cache cleared & npm run dev started');
    });


    // ✅ تحديث: استخدام DashboardController بدلاً من عرض مباشر
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    // include other route files
    require __DIR__ . '/map.php';
    require __DIR__ . '/model_history.php';
    require __DIR__ . '/report.php';
    require __DIR__ . '/building.php';
    require __DIR__ . '/customer.php';
    require __DIR__ . '/service.php';
    require __DIR__ . '/payment.php';
    require __DIR__ . '/expense.php';
    require __DIR__ . '/income.php';
    require __DIR__ . '/cash_account.php';
    require __DIR__ . '/cash_transfer.php';
    require __DIR__ . '/contract.php';
    require __DIR__ . '/transfer.php';
    require __DIR__ . '/hr.php';
    require __DIR__ . '/user.php';
    require __DIR__ . '/role.php';
    require __DIR__ . '/notification.php';
    require __DIR__ . '/profile.php';
});

// ✅ auth routes (login, register, reset…)
require __DIR__ . '/auth.php';
