<?php

declare(strict_types=1);

namespace Tests\Application\Service;

use App\Domain\Discount\EveryFifthProductFreeStrategy;
use App\Domain\Model\Cart;
use App\Domain\Model\ProductModel;
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
            $cart->addProduct(new ProductModel('Candy', 10.0));
        }

        $finalPrice = $strategy->applyDiscount($cart);

        $this->assertEquals(40.0, $finalPrice);
    }
}
