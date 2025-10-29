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
        // Diretiva personalizada para valores em reais
        Blade::directive('real', function ($amount) {
            return "<?php echo 'R$ ' . number_format($amount, 2, ',', '.'); ?>";
        });
    }
}
