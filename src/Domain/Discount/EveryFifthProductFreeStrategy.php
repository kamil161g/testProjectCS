<?php

declare(strict_types=1);

namespace App\Domain\Discount;

use App\Domain\Model\Cart;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class EveryFifthProductFreeStrategy implements DiscountStrategyInterface
{
    public function applyDiscount(Cart $cart): float
    {
        $totalPrice = $cart->getTotalPrice();
        $productCounts = $cart->getProductCounts();

        foreach ($productCounts as $productName => $count) {
            $productPrice = array_values(array_filter($cart->getProducts(), static fn($product) => $product->getName() === $productName))[0]->getPrice();
            $totalPrice -= floor($count / 5) * $productPrice;
        }

        return $totalPrice;
    }
}
