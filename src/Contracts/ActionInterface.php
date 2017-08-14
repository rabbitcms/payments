<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

use JsonSerializable;

/**
 * Class ActionInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface ActionInterface extends ContinuableInterface, JsonSerializable
{
    const ACTION_OPEN = 'OPEN';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /**
     * @return string
     */
    public function getAction(): string;

    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return array
     */
    public function getData(): array;
}
