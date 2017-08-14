<?php
declare(strict_types=1);

namespace RabbitCMS\Payments;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

/**
 * Class ModuleProvider
 *
 * @package DtKt\Payments
 */
class ModuleProvider extends ServiceProvider
{
    /**
     * Register provider.
     */
    public function register()
    {
        $this->app->singleton('payments', function (Container $app) {
            return new Factory($app);
        });
        $this->app->alias('payments', Factory::class);

        $path = dirname(__DIR__) . '/config/payments.php';

        if (is_file($path)) {
            $this->mergeConfigFrom($path, 'payments');

            $this->publishes([$path => config_path('payments.php')]);
        }

        $path = dirname(__DIR__) . '/config/config.php';

        if (is_file($path)) {
            $this->mergeConfigFrom($path, 'module.payments');

            $this->publishes([$path => config_path('module/payments.php')]);
        }

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return ['payments', Factory::class];
    }
}
