<?php

namespace App\Providers;

use App\Models\Contract\Contract;
use App\Models\Customer\Customer;
use App\Models\Payment\Payment;
use App\Observers\ContractObserver;
use App\Observers\CustomerObserver;
use App\Observers\PaymentObserver;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        $mainPath = database_path('migrations');
        $directories = glob($mainPath . '/*', GLOB_ONLYDIR);
        $paths = array_merge([$mainPath], $directories);
        $this->loadMigrationsFrom($paths);
        Contract::observe(ContractObserver::class);
        Customer::observe(CustomerObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
