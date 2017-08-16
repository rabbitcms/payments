<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Entities;

use Illuminate\Database\Eloquent\Model;
use RabbitCMS\Payments\Contracts\CardTokenInterface;
use RabbitCMS\Payments\Contracts\PaymentProviderInterface;
use RabbitCMS\Payments\Facade\Payments;

/**
 * Class CardToken
 *
 * @package       RabbitCMS\Payments\Entities
 * @property-read int    $id
 * @property-read string $driver
 * @property-read string $client
 * @property-read string $card
 * @property-read string $token
 * @property-read array $data
 */
class CardToken extends Model implements CardTokenInterface
{
    protected $table = 'payments_tokens';

    protected $fillable = [
        'driver',
        'client',
        'card',
        'token',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    /**
     * @return PaymentProviderInterface
     */
    public function getProvider(): PaymentProviderInterface
    {
        return Payments::driver($this->driver);
    }

    /**
     * Get client identifier.
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getCard(): string
    {
        return $this->card;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data ?? [];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->getKey();
    }
}
