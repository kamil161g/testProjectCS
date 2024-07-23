<?php

namespace App\Domain\Discount;

use App\Domain\Model\Cart;
use App\Domain\ValueObject\Money;

interface DiscountStrategyInterface
{
    public function applyDiscount(Cart $cart): Money;

}