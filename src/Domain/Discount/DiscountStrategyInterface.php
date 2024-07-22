<?php

namespace App\Domain\Discount;

use App\Domain\Model\Cart;

interface DiscountStrategyInterface
{
    public function applyDiscount(Cart $cart): float;

}