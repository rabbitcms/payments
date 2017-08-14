<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

/**
 * Interface ClientInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface ClientInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @return string
     */
    public function getLastName(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getPhone(): string;

    /**
     * @return string
     */
    public function getCountry(): string;

    /**
     * @return string
     */
    public function getCity(): string;

    /**
     * @return string
     */
    public function getAddress(): string;

    /**
     * @return string
     */
    public function getPostalCode(): string;
}
