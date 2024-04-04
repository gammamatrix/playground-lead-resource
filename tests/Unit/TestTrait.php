<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Tests\Unit\Playground\Lead\Resource;

use Laravel\Sanctum\SanctumServiceProvider;
use Playground\Auth\ServiceProvider as PlaygroundAuthServiceProvider;
use Playground\Http\ServiceProvider as PlaygroundHttpServiceProvider;
use Playground\Lead\Resource\ServiceProvider;
use Playground\Lead\ServiceProvider as PlaygroundLeadServiceProvider;
use Playground\ServiceProvider as PlaygroundServiceProvider;

/**
 * \Tests\Unit\Playground\Lead\Resource\TestTrait
 */
trait TestTrait
{
    protected function getPackageProviders($app)
    {
        return [
            PlaygroundAuthServiceProvider::class,
            PlaygroundHttpServiceProvider::class,
            PlaygroundLeadServiceProvider::class,
            PlaygroundServiceProvider::class,
            ServiceProvider::class,
            SanctumServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', 'Playground\\Models\\User');
        $app['config']->set('playground-auth.verify', 'user');
        $app['config']->set('auth.testing.password', 'password');
        $app['config']->set('auth.testing.hashed', false);

        $app['config']->set('playground-lead.load.migrations', true);
    }
}
