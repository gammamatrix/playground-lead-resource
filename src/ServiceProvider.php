<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Lead\Resource;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Log;

/**
 * \Playground\Lead\Resource\ServiceProvider
 */
class ServiceProvider extends AuthServiceProvider
{
    public const VERSION = '73.0.0';

    public string $package = 'playground-lead-resource';

    /**
     * Bootstrap any package services.
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        /**
         * @var array<string, mixed> $config
         */
        $config = config($this->package);

        if (! empty($config['load']) && is_array($config['load'])) {

            if (! empty($config['load']['translations'])) {
                $this->loadTranslationsFrom(
                    dirname(__DIR__).'/lang',
                    $this->package
                );
            }

            if (! empty($config['load']['policies'])
                && ! empty($config['policies'])
                && is_array($config['policies'])
            ) {
                $this->setPolicies($config['policies']);
                $this->registerPolicies();
            }

            if (! empty($config['load']['routes'])
                && ! empty($config['routes'])
                && is_array($config['routes'])
            ) {
                $this->routes($config['routes']);
            }

            if (! empty($config['load']['views'])) {
                $this->loadViewsFrom(
                    dirname(__DIR__).'/resources/views',
                    $this->package
                );
            }
        }

        if ($this->app->runningInConsole()) {
            // Publish configuration
            $this->publishes([
                sprintf('%1$s/config/%2$s.php', dirname(__DIR__), $this->package) => config_path(sprintf('%1$s.php', $this->package)),
            ], 'playground-config');

            // Publish routes
            $this->publishes([
                dirname(__DIR__).'/routes' => base_path('routes/playground-lead-resource'),
            ], 'playground-routes');
        }

        if (! empty($config['about'])) {
            $this->about();
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/config/playground-lead-resource.php',
            $this->package
        );
    }

    /**
     * Set the application's policies from the configuration.
     *
     * @param array<class-string, class-string> $policies
     */
    public function setPolicies(array $policies): void
    {
        foreach ($policies as $model => $policy) {
            if (! is_string($model) || ! class_exists($model)) {
                Log::error('Expecting the model to exist for the policy.', [
                    '__METHOD__' => __METHOD__,
                    'model' => is_string($model) ? $model : gettype($model),
                    'policy' => is_string($policy) ? $policy : gettype($policy),
                    'policies' => $policies,
                ]);

                continue;
            }
            if (! is_string($policy) || ! class_exists($policy)) {
                Log::error('Expecting the policy to exist for the model.', [
                    '__METHOD__' => __METHOD__,
                    'model' => is_string($model) ? $model : gettype($model),
                    'policy' => is_string($policy) ? $policy : gettype($policy),
                    'policies' => $policies,
                ]);

                continue;
            }
            $this->policies[$model] = $policy;
        }
    }

    /**
     * @param array<string, mixed> $config
     */
    public function routes(array $config): void
    {
        if (! empty($config['lead'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/lead.php');
        }
        if (! empty($config['campaigns'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/campaigns.php');
        }
        if (! empty($config['goals'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/goals.php');
        }
        if (! empty($config['leads'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/leads.php');
        }
        if (! empty($config['opportunities'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/opportunities.php');
        }
        if (! empty($config['plans'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/plans.php');
        }
        if (! empty($config['regions'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/regions.php');
        }
        if (! empty($config['reports'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/reports.php');
        }
        if (! empty($config['sources'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/sources.php');
        }
        if (! empty($config['tasks'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/tasks.php');
        }
        if (! empty($config['teams'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/teams.php');
        }
        if (! empty($config['teammates'])) {
            $this->loadRoutesFrom(dirname(__DIR__).'/routes/teammates.php');
        }
    }

    public function about(): void
    {
        $config = config($this->package);
        $config = is_array($config) ? $config : [];

        $load = ! empty($config['load']) && is_array($config['load']) ? $config['load'] : [];

        $middleware = ! empty($config['middleware']) && is_array($config['middleware']) ? $config['middleware'] : [];

        $routes = ! empty($config['routes']) && is_array($config['routes']) ? $config['routes'] : [];

        $sitemap = ! empty($config['sitemap']) && is_array($config['sitemap']) ? $config['sitemap'] : [];

        AboutCommand::add('Playground: Lead Resource', fn () => [

            '<fg=yellow;options=bold>Load</> Policies' => ! empty($load['policies']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=yellow;options=bold>Load</> Routes' => ! empty($load['routes']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=yellow;options=bold>Load</> Translations' => ! empty($load['translations']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=yellow;options=bold>Load</> Views' => ! empty($load['views']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',

            '<fg=yellow;options=bold>Middleware</> auth' => ! empty($middleware['auth']) ? sprintf('%s', json_encode($middleware['auth'])) : '',
            '<fg=yellow;options=bold>Middleware</> default' => ! empty($middleware['default']) ? sprintf('%s', json_encode($middleware['default'])) : '',
            '<fg=yellow;options=bold>Middleware</> guest' => ! empty($middleware['guest']) ? sprintf('%s', json_encode($middleware['guest'])) : '',

            '<fg=blue;options=bold>View</> [Blade]' => ! empty($config['blade']) ? sprintf('[%s]', $config['blade']) : '',

            '<fg=magenta;options=bold>Sitemap</> Views' => ! empty($sitemap['enable']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=magenta;options=bold>Sitemap</> Guest' => ! empty($sitemap['guest']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=magenta;options=bold>Sitemap</> User' => ! empty($sitemap['user']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=magenta;options=bold>Sitemap</> [view]' => sprintf('[%s]', $sitemap['view']),

            '<fg=red;options=bold>Route</> lead' => ! empty($routes['lead']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> campaigns' => ! empty($routes['campaigns']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> goals' => ! empty($routes['goals']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> leads' => ! empty($routes['leads']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> opportunities' => ! empty($routes['opportunities']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> plans' => ! empty($routes['plans']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> regions' => ! empty($routes['regions']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> reports' => ! empty($routes['reports']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> sources' => ! empty($routes['sources']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> tasks' => ! empty($routes['tasks']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> teams' => ! empty($routes['teams']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',
            '<fg=red;options=bold>Route</> teammates' => ! empty($routes['teammates']) ? '<fg=green;options=bold>ENABLED</>' : '<fg=yellow;options=bold>DISABLED</>',

            'Package' => $this->package,
            'Version' => ServiceProvider::VERSION,
        ]);
    }
}
