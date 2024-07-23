<?php

declare(strict_types=1);

namespace Tests\Application\Service;

use App\Domain\Discount\EveryFifthProductFreeStrategy;
use App\Domain\Model\Cart;
use App\Domain\Model\ProductModel;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class EveryFifthProductFreeStrategyTest extends TestCase
{
    public function testApplyDiscount(): void
    {
        $cart = new Cart();
        $strategy = new EveryFifthProductFreeStrategy();

        for ($i = 0; $i < 5; $i++) {
            $cart->addProduct(new ProductModel('5a1464b6-1245-4f81-b05b-08d0f7dc788', 'Candy', new Money(1000)),
                1);
        }

        $finalPrice = $strategy->applyDiscount($cart);

        $this->assertEquals(new Money(4000), $finalPrice);
    }
}
