<?php
declare(strict_types=1);

namespace RabbitCMS\Payments;

use Illuminate\Support\Manager;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use RabbitCMS\Modules\Support\ModuleDetect;
use RabbitCMS\Payments\Contracts\InvoiceInterface;
use RabbitCMS\Payments\Contracts\PaymentProviderInterface;

/**
 * Class Factory
 *
 * @package DtKt\Payments
 * @method PaymentProviderInterface driver($driver = null)
 */
class Factory extends Manager
{
    use ModuleDetect;

    protected $resolvers = [];

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return 'default';
    }

    /**
     * Create a new driver instance.
     *
     * @param  string $driver
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        $config = config("module.payments.{$driver}", []);
        $provider = $config['provider'] ?? null;
        // We'll check to see if a creator method exists for the given driver. If not we
        // will check for a custom driver creator, which allows developers to create
        // drivers using their own customized driver creator Closure to create it.
        if (isset($this->customCreators[$provider])) {
            return tap(
                $this->callCustomCreator($provider, $config),
                function (PaymentProviderInterface $provider) use ($driver) {
                    $provider->setLogger(
                        new Logger($driver, [new RotatingFileHandler(storage_path("logs/{$driver}.log"))])
                    );
                }
            );
        }
        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    /**
     * Call a custom driver creator.
     *
     * @param string $driver
     * @param array  $config
     *
     * @return mixed
     */
    protected function callCustomCreator($driver, array $config = [])
    {
        return $this->customCreators[$driver]($this->app, $config);
    }

    /**
     * @param InvoiceInterface $invoice
     */
    public function process(InvoiceInterface $invoice)
    {
        //todo
    }
}
