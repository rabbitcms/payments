<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

/**
 * Interface OrderInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface OrderInterface
{
    /**
     * @param TransactionInterface $transaction
     *
     * @return void
     */
    public function paymentStatus(TransactionInterface $transaction);

    /**
     * @return PaymentInterface
     */
    public function getPayment(): PaymentInterface;

    /**
     * @return string
     */
    public function getOrderId(): string;
}
