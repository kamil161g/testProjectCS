<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Money;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class Cart
{
    private array $items = [];

    public function addProduct(ProductModel $product, int $quantity = 1): void
    {
        $productId = $product->getId();
        if (isset($this->items[$productId])) {
            $this->items[$productId]->incrementQuantity($quantity);
        } else {
            $this->items[$productId] = new CartItem($product, $quantity);
        }
    }

    public function removeProduct(string $productId, int $quantity = 1): void
    {
        if (isset($this->items[$productId])) {
            $item = $this->items[$productId];
            $item->incrementQuantity(-$quantity);
            if ($item->getQuantity() <= 0) {
                unset($this->items[$productId]);
            }
        }
    }

    public function getTotalPrice(): Money
    {
        $total = new Money(0);
        foreach ($this->items as $item) {
            $total = $total->add($item->getTotalPrice());
        }
        return $total;
    }

    public function getProductCounts(): array
    {
        $counts = [];
        foreach ($this->items as $productId => $item) {
            $counts[$productId] = $item->getQuantity();
        }
        return $counts;
    }

    public function getProductById(string $id): ?CartItem
    {
        return $this->items[$id] ?? null;
    }
}
