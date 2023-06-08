<?php

namespace App\Providers\Coins;

use Illuminate\Support\ServiceProvider;
use App\Helpers\CoinHelper;
class CoinServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CoinHelper::class, function ($app) {
            return new CoinHelper();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
