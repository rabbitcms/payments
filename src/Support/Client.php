<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Support;

use RabbitCMS\Payments\Contracts\ClientInterface;

/**
 * Class Client
 *
 * @package RabbitCMS\Payments\Support
 */
class Client implements ClientInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $country = '';

    /**
     * @var string
     */
    protected $city = '';

    /**
     * @var string
     */
    protected $address = '';

    /**
     * @var string
     */
    protected $postalCode = '';

    /**
     * @var string
     */
    protected $email = '';

    /**
     * @var string
     */
    protected $phone = '';

    /**
     * Client constructor.
     *
     * @param string $id
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(string $id, string $firstName, string $lastName = '')
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $country
     *
     * @return Client
     */
    public function setCountry(string $country): Client
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $city
     *
     * @return Client
     */
    public function setCity(string $city): Client
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $address
     *
     * @return Client
     */
    public function setAddress(string $address): Client
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param string $postalCode
     *
     * @return Client
     */
    public function setPostalCode(string $postalCode): Client
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @param string $email
     *
     * @return Client
     */
    public function setEmail(string $email): Client
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $phone
     *
     * @return Client
     */
    public function setPhone(string $phone): Client
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
}
