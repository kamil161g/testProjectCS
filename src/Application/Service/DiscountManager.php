<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Model\Cart;
use App\Domain\ValueObject\Money;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class DiscountManager
{
    public function __construct(private iterable $strategies) {}

    public function applyBestDiscount(Cart $cart): Money
    {
        $bestPrice = $cart->getTotalPrice();

        foreach ($this->strategies as $strategy) {
            $discountedPrice = $strategy->applyDiscount($cart);
            if ($discountedPrice->getAmount() < $bestPrice->getAmount()) {
                $bestPrice = $discountedPrice;
            }
        }

        return $bestPrice;
    }
}