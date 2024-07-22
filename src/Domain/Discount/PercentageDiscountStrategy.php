<?php

declare(strict_types=1);

namespace App\Domain\Discount;

use App\Domain\Model\Cart;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class PercentageDiscountStrategy implements DiscountStrategyInterface
{
    public function applyDiscount(Cart $cart): float
    {
        $totalPrice = $cart->getTotalPrice();

        if ($totalPrice > 100) {
            $totalPrice *= 0.9;
        }

        return $totalPrice;
    }
}
