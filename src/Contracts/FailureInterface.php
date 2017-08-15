<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

/**
 * Interface FailureInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface FailureInterface extends ContinuableInterface
{
    /**
     * @return string
     */
    public function getMessage(): string;
}