<?php

declare(strict_types=1);

namespace App\Domain\Model;

use InvalidArgumentException;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class Cart
{
    private array $products;

    public function __construct()
    {
        $this->products = [];
    }

    public function addProduct(ProductModel $product): void
    {
        $this->products[] = $product;
    }

    public function getTotalPrice(): float
    {
        return array_reduce($this->products, fn($sum, $product) => $sum + $product->getPrice(), 0);
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getProductCounts(): array
    {
        $counts = [];
        foreach ($this->products as $product) {
            if (!isset($counts[$product->getName()])) {
                $counts[$product->getName()] = 0;
            }
            $counts[$product->getName()]++;
        }
        return $counts;
    }

    public function getProductPrice(string $productName): float
    {
        foreach ($this->products as $product) {
            if ($product->getName() === $productName) {
                return $product->getPrice();
            }
        }
        throw new InvalidArgumentException("Product with name $productName not found in cart.");
    }

    public function removeProduct(ProductModel $product): void
    {
        foreach ($this->products as $key => $existingProduct) {
            if ($existingProduct->getName() === $product->getName() && $existingProduct->getPrice() === $product->getPrice()) {
                unset($this->products[$key]);
                $this->products = array_values($this->products);
                break;
            }
        }
    }
}
