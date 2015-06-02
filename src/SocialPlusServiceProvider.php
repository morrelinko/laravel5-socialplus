<?php

namespace Morrelinko\SocialPlus;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class SocialPlusServiceProvider extends ServiceProvider
{
    public function boot()
    {
        require __DIR__ . '/../routes/routes.php';

        $this->publishes([
            __DIR__ . '/../config' => base_path('config')
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSocialPlus();
    }

    protected function registerSocialPlus()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/socialplus.php', 'socialplus'
        );

        $this->app->singleton('socialplus', function (Application $app) {
            $socialPlus = new SocialPlus($app['session']);

            $socialPlus->setSocialite(
                $this->app->make('Laravel\Socialite\Contracts\Factory')
            );

            foreach ($this->app['config']->get('socialplus.authorize_handlers') as $handler) {
                $socialPlus->registerAuthorizeHandler(
                    $this->app->make($handler)
                );
            }

            return $socialPlus;
        });

        $this->app->bind('Morrelinko\SocialPlus\SocialPlus', function () {
            return $this->app['socialplus'];
        });
    }

    public function provides()
    {
        return [
            'socialplus'
        ];
    }
}
