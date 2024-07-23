<?php

declare(strict_types=1);

namespace App\Application\DTO;

use Webmozart\Assert\Assert;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class AddProductDTO
{
    private array $products;

    public function __construct(array $products)
    {
        Assert::isArray($products);
        foreach ($products as $product) {
            Assert::keyExists($product, 'productId');
            Assert::keyExists($product, 'quantity');
            Assert::string($product['productId']);
            Assert::integer($product['quantity']);
        }
        $this->products = $products;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public static function fromArray(array $data): self
    {
        Assert::keyExists($data, 'products');
        Assert::isArray($data['products']);
        foreach ($data['products'] as $product) {
            Assert::keyExists($product, 'productId');
            Assert::keyExists($product, 'quantity');
            Assert::string($product['productId']);
            Assert::integer($product['quantity']);
        }

        return new self($data['products']);
    }
}
