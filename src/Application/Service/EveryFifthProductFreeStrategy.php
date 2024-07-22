<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Discount\DiscountStrategyInterface;
use App\Domain\Model\Cart;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class EveryFifthProductFreeStrategy implements DiscountStrategyInterface
{
    public function applyDiscount(Cart $cart): float
    {
        $products = $cart->getProducts();
        $productCounts = [];
        $totalDiscount = 0;

        foreach ($products as $product) {
            $productName = $product->getName();
            if (!isset($productCounts[$productName])) {
                $productCounts[$productName] = 0;
            }
            $productCounts[$productName]++;
        }

        foreach ($productCounts as $productName => $count) {
            $freeProducts = intdiv($count, 5);
            $productPrice = $cart->getProductPrice($productName);
            $totalDiscount += $freeProducts * $productPrice;
        }

        return $cart->getTotalPrice() - $totalDiscount;
    }
}
