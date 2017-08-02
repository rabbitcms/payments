<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Support;

use RabbitCMS\Payments\Contracts\ProductInterface;

/**
 * Class Product
 *
 * @package RabbitCMS\Payments\Support
 */
class Product implements ProductInterface
{
    /**
     * @var string
     */
    protected $category;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $url;

    /**
     * Product constructor.
     *
     * @param string $category
     * @param string $name
     * @param string $description
     * @param string $url
     */
    public function __construct(string $category, string $name, string $description = '', string $url = '')
    {
        $this->category = $category;
        $this->name = $name;
        $this->description = $description;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
