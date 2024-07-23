<?php

declare(strict_types=1);

namespace App\Domain\Discount;

use App\Domain\Model\Cart;
use App\Domain\ValueObject\Money;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class EveryFifthProductFreeStrategy implements DiscountStrategyInterface
{
    public function applyDiscount(Cart $cart): Money
    {
        $totalPrice = $cart->getTotalPrice();
        $productCounts = $cart->getProductCounts();

        foreach ($productCounts as $productId => $count) {
            $cartItem = $cart->getProductById($productId);
            if ($cartItem) {
                $productPrice = $cartItem->getProduct()->getPrice();
                $discount = $productPrice->multiply((int)floor($count / 5));
                $totalPrice = $totalPrice->subtract($discount);
            }
        }

        return $totalPrice;
    }
}
