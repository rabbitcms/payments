<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Interface PaymentProviderInterface
 *
 * @package DtKt\Payments\Contracts
 */
interface PaymentProviderInterface extends LoggerAwareInterface
{
    /**
     * @return string
     */
    public function getProviderName(): string;

    /**
     * @return string
     */
    public function getShop(): string;

    /**
     * @param OrderInterface $order
     *
     * @param callable|null  $callback
     *
     * @return ContinuableInterface
     */
    public function createPayment(OrderInterface $order, callable $callback = null): ContinuableInterface;

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function callback(ServerRequestInterface $request): ResponseInterface;
}
