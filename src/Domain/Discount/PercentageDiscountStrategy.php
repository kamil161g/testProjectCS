<?php

declare(strict_types=1);

namespace App\Domain\Discount;

use App\Domain\Model\Cart;
use App\Domain\ValueObject\Money;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class PercentageDiscountStrategy implements DiscountStrategyInterface
{
    public function applyDiscount(Cart $cart): Money
    {
        $totalPrice = $cart->getTotalPrice();

        if ($totalPrice->getAmount() > 10000) {
            $discount = $totalPrice->multiply(0.1);
            $totalPrice = $totalPrice->subtract($discount);
        }

        return $totalPrice;
    }
}
