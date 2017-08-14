<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Support;

use Laravel\Socialite\Two\ProviderInterface;
use RabbitCMS\Payments\Contracts\ActionInterface;
use RabbitCMS\Payments\Contracts\PaymentProviderInterface;

/**
 * Class Action
 *
 * @package RabbitCMS\Payments\Support
 */
class Action implements ActionInterface
{
    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $method = self::METHOD_GET;

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var array
     */
    protected $data;

    /**
     * Action constructor.
     *
     * @param PaymentProviderInterface $provider
     * @param string                   $action
     * @param array                    $data
     */
    public function __construct(
        PaymentProviderInterface $provider,
        string $action = self::ACTION_OPEN,
        array $data = []
    ) {
        $this->provider = $provider;
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * @inheritdoc
     */
    public function getProvider(): PaymentProviderInterface
    {
        return $this->provider;
    }

    /**
     * @inheritdoc
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @inheritdoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param mixed $method
     *
     * @return Action
     */
    public function setMethod($method): Action
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param string $url
     *
     * @return Action
     */
    public function setUrl(string $url): Action
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param array $data
     *
     * @return Action
     */
    public function setData(array $data): Action
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'provider' => $this->getProvider()->getProviderName(),
            'shop' => $this->getProvider()->getShop(),
            'action' => $this->getAction(),
            'method' => $this->getMethod(),
            'url' => $this->getUrl(),
            'data' => $this->getData()
        ];
    }
}
