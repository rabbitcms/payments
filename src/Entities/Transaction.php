<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use RabbitCMS\Payments\Contracts\OrderInterface;
use RabbitCMS\Payments\Contracts\TransactionInterface;

/**
 * Class Transaction
 *
 * @package RabbitCMS\Payments\Entities
 * @property-read int    $id
 * @property-read string $driver
 * @property-read int    $status
 *
 *
 */
class Transaction extends Model implements TransactionInterface
{
    protected $table = 'payments_transactions';

    protected $fillable = [
        'driver',
        'status'
    ];

    protected $casts = [
        'status' => 'int'
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING
    ];

    /**
     * @return MorphTo
     */
    public function order(): MorphTo
    {
        return $this->morphTo('order');
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface
    {
        return $this->order()->getResults();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
