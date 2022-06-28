<?php

namespace App\Providers;

use App\Repositories\Contracts\SimulationRepositoryInterface;
use App\Repositories\SimulationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SimulationRepositoryInterface::class, SimulationRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
