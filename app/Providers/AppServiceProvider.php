<?php

namespace App\Providers;

use App\Support\Facades\Form;
use App\Support\Html\FormBuilder;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FormBuilder::class);

        // Las vistas heredadas de Laravel 4 usan Form:: sin namespace.
        AliasLoader::getInstance()->alias('Form', Form::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Laravel 4 generaba la paginación con el marcado de Bootstrap 3.
        Paginator::useBootstrapThree();

        // Equivalente a URL::forceSchema('https') que estaba comentado en filters.php.
        if (config('app.force_https')) {
            URL::forceScheme('https');
        }
    }
}
