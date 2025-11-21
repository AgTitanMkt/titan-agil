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

        Carbon::setLocale('pt_BR');

        // Diretiva personalizada para valores
        Blade::directive('dollar', function ($amount) {
            return "<?php echo 'USD ' . number_format($amount, 2, ',', '.'); ?>";
        });
        Blade::directive('real', function ($amount) {
            return "<?php echo 'R$ ' . number_format($amount, 2, ',', '.'); ?>";
        });
    }
}
