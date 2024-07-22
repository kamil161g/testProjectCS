<?php

declare(strict_types=1);

namespace App\Domain\Model;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class Cart
{
    /**
     * @var ProductModel[]
     */
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
        return array_reduce($this->products, static fn($sum, $product) => $sum + $product->getPrice(), 0);
    }

    public function getProducts(): array
    {
        $products = [];
        foreach ($this->products as $product) {
            $products[$product->getId()] = $product;
        }
        return $products;
    }

    public function getProductCounts(): array
    {
        $counts = [];
        foreach ($this->products as $product) {
            $id = $product->getId();
            if (!isset($counts[$id])) {
                $counts[$id] = 0;
            }
            $counts[$id]++;
        }
        return $counts;
    }

    public function getProductById(string $id): ?ProductModel
    {
        foreach ($this->products as $product) {
            if ($product->getId() === $id) {
                return $product;
            }
        }
        return null;
    }

    public function removeProduct(string $productId): void
    {
        foreach ($this->products as $key => $product) {
            if ($product->getId() === $productId) {
                unset($this->products[$key]);
            }
        }
    }
}
