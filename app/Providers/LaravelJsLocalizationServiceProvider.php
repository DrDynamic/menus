<?php

namespace App\Providers;

class LaravelJsLocalizationServiceProvider extends \Mariuzzo\LaravelJsLocalization\LaravelJsLocalizationServiceProvider
{

    public function register()
    {
        // Bind the Laravel JS Localization command into the app IOC.
        $this->app->singleton('localization.js', function ($app) {
            $app = $this->app;
            $laravelMajorVersion = (int)$app::VERSION;

            $files = $app['files'];

            if ($laravelMajorVersion >= 9) {
                $langs = $app['path.base'] . '/lang';
            } elseif ($laravelMajorVersion >= 5) {
                $langs = $app['path.base'] . '/resources/lang';
            } elseif ($laravelMajorVersion === 4) {
                $langs = $app['path.base'] . '/app/lang';
            }
            $messages = $app['config']->get('localization-js.messages');
            $generator = new \Mariuzzo\LaravelJsLocalization\Generators\LangJsGenerator($files, $langs, $messages);

            return new \Mariuzzo\LaravelJsLocalization\Commands\LangJsCommand($generator);
        });

        // Bind the Laravel JS Localization command into Laravel Artisan.
        $this->commands('localization.js');
    }

}
