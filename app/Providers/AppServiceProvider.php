<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        Blade::directive('int_number', function ($amount) {
            return "<?php echo number_format($amount, 0, ',', '.'); ?>";
        });
        // Diretiva personalizada para valores em reais
        Blade::directive('dollar', function ($amount) {
            return "<?php echo 'USD ' . number_format($amount, 2, ',', '.'); ?>";
        });
    }
}
