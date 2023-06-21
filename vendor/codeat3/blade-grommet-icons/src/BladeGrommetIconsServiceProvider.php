<?php

declare(strict_types=1);

namespace Codeat3\BladeGrommetIcons;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class BladeGrommetIconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('blade-grommet-icons', []);

            $factory->add('grommet-icons', array_merge(['path' => __DIR__ . '/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blade-grommet-icons.php', 'blade-grommet-icons');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/svg' => public_path('vendor/blade-grommet-icons'),
            ], 'blade-grommet-icons');

            $this->publishes([
                __DIR__ . '/../config/blade-grommet-icons.php' => $this->app->configPath('blade-grommet-icons.php'),
            ], 'blade-grommet-icons-config');
        }
    }
}
