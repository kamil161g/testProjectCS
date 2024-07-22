<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Discount\DiscountStrategyInterface;
use App\Domain\Model\Cart;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class TotalPricePercentageDiscountStrategy implements DiscountStrategyInterface
{
    public function applyDiscount(Cart $cart): float
    {
        $totalPrice = $cart->getTotalPrice();
        if ($totalPrice > 100) {
            $discount = $totalPrice * 0.10;
            return $totalPrice - $discount;
        }
        return $totalPrice;
    }
}
