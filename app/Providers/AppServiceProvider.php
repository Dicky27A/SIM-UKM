<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use App\Repository\PricingRepository;
use App\Repository\PricingRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(PricingRepositoryInterface::class, PricingRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Transaction::observe(TransactionObserver::class);
    }
}
