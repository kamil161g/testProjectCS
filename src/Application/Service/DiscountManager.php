<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Model\Cart;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class DiscountManager
{
    public function __construct(private iterable $strategies) {}

    public function applyBestDiscount(Cart $cart): float
    {
        $bestPrice = $cart->getTotalPrice();

        foreach ($this->strategies as $strategy) {
            $discountedPrice = $strategy->applyDiscount($cart);
            if ($discountedPrice < $bestPrice) {
                $bestPrice = $discountedPrice;
            }
        }

        return $bestPrice;
    }
}