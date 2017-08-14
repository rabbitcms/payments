<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class Payments
 *
 * @package RabbitCMS\Payments\Facade
 */
class Payments extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'payments';
    }
}
