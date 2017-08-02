<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

use DateTimeInterface;

/**
 * Interface SubscribeOrderInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface SubscribeOrderInterface extends OrderInterface
{
    /**
     * @return DateTimeInterface
     */
    public function getNextSubscribeDate(): DateTimeInterface;
}
