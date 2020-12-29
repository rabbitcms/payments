<?php

declare(strict_types=1);

namespace RabbitCMS\Payments\Facade;

use Illuminate\Support\Facades\Facade;
use RabbitCMS\Payments\Contracts\PaymentProviderInterface;

/**
 * Class Payments
 *
 * @method static PaymentProviderInterface driver(string $driver)
 */
class Payments extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'payments';
    }
}
