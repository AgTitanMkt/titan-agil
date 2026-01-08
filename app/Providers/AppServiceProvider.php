<?php

namespace App\Providers;

use Carbon\Carbon;
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
        Carbon::setLocale('pt_BR');
        Blade::directive('real', function ($amount) {
            return "<?php echo 'R$ ' . number_format($amount, 2, ',', '.'); ?>";
        });

        Blade::directive('dollar', function ($amount) {
            return "<?php echo 'USD ' . number_format($amount, 2, ',', '.'); ?>";
        });

        Blade::directive('dollar', function ($amount) {
            return "<?php echo 'USD ' . number_format($amount, 2, ',', '.'); ?>";
        });

        Blade::directive('dollarK', function ($amount) {
            return "<?php echo '$' . number_format($amount/1000, 0, '.', ',') . 'K'; ?>";
        });

        Blade::directive('dollarM', function ($amount) {
            return "<?php echo '$' . number_format($amount/1000000, 2, '.', ',') . 'M'; ?>";
        });
        
        Blade::directive('percent', function ($value) {
            return "<?php echo number_format($value*100, 2, ',','.') . '%'; ?>";
        });
        
        Blade::directive('percent0', function ($value) {
            return "<?php echo number_format($value*100, 0, ',') . '%'; ?>";
        });


    }
}
