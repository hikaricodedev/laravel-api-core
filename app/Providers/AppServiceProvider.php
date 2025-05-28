<?php

namespace App\Providers;

use App\Exceptions\ApiAuthenticationExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Exceptions\Reportable;

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

    }
}
