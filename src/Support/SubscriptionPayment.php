<?php

declare(strict_types=1);

namespace RabbitCMS\Payments\Support;

use DateTime;
use DateTimeInterface;
use RabbitCMS\Payments\Contracts\SubscribePaymentInterface;

class SubscriptionPayment extends Payment implements SubscribePaymentInterface
{
    /**
     * @var int
     */
    private $periodic;

    /**
     * @var DateTimeInterface
     */
    private $date;

    public function __construct(string $currency, float $amount, string $description, Client $client, int $periodic, ?DateTimeInterface $date = null)
    {
        parent::__construct($currency, $amount, $description, $client);

        $this->periodic = $periodic;
        $this->date = $date ?? new DateTime();
    }

    public function getSubscribeStart(): DateTimeInterface
    {
        return $this->date;
    }

    public function getSubscribePeriodic(): int
    {
        return $this->periodic;
    }
}
