<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

use DateTimeInterface;

/**
 * Interface SubscribePaymentInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface SubscribePaymentInterface extends PaymentInterface
{
    const PERIODICITY_CUSTOM = 0;
    const PERIODICITY_MONTH = 1;
    const PERIODICITY_QUARTER = 2;
    const PERIODICITY_HALF_YEAR = 3;
    const PERIODICITY_YEAR = 4;

    /**
     * @return DateTimeInterface
     */
    public function getSubscribeStart(): DateTimeInterface;

    /**
     * @return int
     */
    public function getSubscribePeriodic(): int;
}
