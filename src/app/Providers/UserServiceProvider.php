<?php

namespace App\Providers;

use App\Http\Services\UserService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(UserService::class, function (Application $app) {
            return new UserService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
