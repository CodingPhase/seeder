<?php

namespace CodingPhase\Seeder;

use Illuminate\Support\ServiceProvider;

class SeederServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->handleConfig();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
    
    /**
     * Handle configuration
     */
    private function handleConfig()
    {
        $configPath = __DIR__ . '/../config/seeding.php';
        $this->publishes([$configPath => config_path('seeding.php')]);
        $this->mergeConfigFrom($configPath, 'seeding');
    }
}
