<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

/**
 * Class ProductInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface ProductInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getCategory(): string;

    /**
     * @return string
     */
    public function getUrl(): string;
}
