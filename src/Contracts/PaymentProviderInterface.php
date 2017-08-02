<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface PaymentProviderInterface
 *
 * @package DtKt\Payments\Contracts
 */
interface PaymentProviderInterface
{
    /**
     * @return string
     */
    public function getProviderName(): string;

    /**
     * @param OrderInterface   $order
     * @param PaymentInterface $payment
     *
     * @return ActionInterface
     */
    public function createPayment(OrderInterface $order, PaymentInterface $payment): ActionInterface;

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function callback(ServerRequestInterface $request): ResponseInterface;
}
