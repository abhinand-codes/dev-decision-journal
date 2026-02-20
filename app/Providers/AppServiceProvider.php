<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\DecisionRepositoryInterface;
use App\Repositories\DecisionRepository;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use App\Repositories\ReviewRepository; // correctly located at app/Repositories/ReviewRepository.php

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            DecisionRepositoryInterface::class,
            DecisionRepository::class
        );

        $this->app->bind(
            ReviewRepositoryInterface::class,
            ReviewRepository::class
        );
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
