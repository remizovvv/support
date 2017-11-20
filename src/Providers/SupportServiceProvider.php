<?php

namespace Omadonex\Support\Providers;

use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $pathRoot = realpath(__DIR__.'/../..');

        $this->loadViewsFrom("$pathRoot/resources/views", 'support');
        $this->loadTranslationsFrom("$pathRoot/resources/lang", 'support');

        $this->publishes([
            "$pathRoot/resources/views" => resource_path('views/vendor/support'),
        ], 'views');
        $this->publishes([
            "$pathRoot/resources/lang" => resource_path('lang/vendor/support'),
        ], 'translations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
