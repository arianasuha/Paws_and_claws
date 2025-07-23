<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Vet;
use App\Policies\VetPolicy;

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
    }

    // app/Providers/AuthServiceProvider.php

    protected $policies = [
        Vet::class => VetPolicy::class,
    ];
}
