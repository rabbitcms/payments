<?php

declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;

interface PaymentProviderInterface extends LoggerAwareInterface
{
    public function getProviderName(): string;

    public function getShop(): string;

    public function createPayment(OrderInterface $order, callable $callback = null, array $options = []): ContinuableInterface;

    public function callback(ServerRequestInterface $request): ResponseInterface;

    public function isValid(): bool;

    public function unsubscribe(OrderInterface $order): bool;
}
