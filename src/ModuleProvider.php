<?php

declare(strict_types=1);

namespace RabbitCMS\Payments;

use DtKt\ServiceManager\ServiceManager;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use RabbitCMS\Payments\ServiceManager\PaymentServiceProvider;

class ModuleProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton('payments', fn(Container $app) => new Factory($app));
        $this->app->alias('payments', Factory::class);

        $path = dirname(__DIR__).'/config/payments.php';

        if (is_file($path)) {
            $this->mergeConfigFrom($path, 'payments');

            $this->publishes([$path => config_path('payments.php')]);
        }

        $path = dirname(__DIR__).'/config/config.php';

        if (is_file($path)) {
            $this->mergeConfigFrom($path, 'module.payments');

            $this->publishes([$path => config_path('module/payments.php')]);
        }

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        $this->app->extend(ServiceManager::class, fn(ServiceManager $manager) => $manager
            ->extend(PaymentServiceProvider::class));
    }

    public function provides(): array
    {
        return ['payments', Factory::class];
    }
}
