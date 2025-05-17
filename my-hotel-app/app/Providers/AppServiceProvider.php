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
        // Standard date formats
        Carbon::macro('standardDate', function () {
            return $this->format('M d, Y');
        });
        
        Carbon::macro('standardDateTime', function () {
            return $this->format('M d, Y g:i A');
        });
        
        Carbon::macro('standardTime', function () {
            return $this->format('g:i A');
        });
        
        // Blade directives for dates
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo Carbon\Carbon::parse($expression)->standardDate(); ?>";
        });
        
        Blade::directive('formatDateTime', function ($expression) {
            return "<?php echo Carbon\Carbon::parse($expression)->standardDateTime(); ?>";
        });
        
        Blade::directive('formatTime', function ($expression) {
            return "<?php echo Carbon\Carbon::parse($expression)->standardTime(); ?>";
        });
    }
}
