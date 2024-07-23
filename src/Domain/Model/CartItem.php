<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\ValueObject\Money;

/**
 * @author Kamil Gąsior <kgasior@weby.pl>
 */
class CartItem
{
    public function __construct(private readonly ProductModel $product, private int $quantity) {}

    public function getProduct(): ProductModel
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function incrementQuantity(int $amount = 1): void
    {
        $this->quantity += $amount;
    }

    public function getTotalPrice(): Money
    {
        return $this->product->getPrice()->multiply($this->quantity);
    }
}
