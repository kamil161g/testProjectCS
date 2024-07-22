<?php

declare(strict_types=1);

namespace Tests\Application\Service;

use App\Application\Service\DiscountManager;
use App\Domain\Model\Cart;
use App\Domain\Discount\DiscountStrategyInterface;
use App\Domain\Model\ProductModel;
use PHPUnit\Framework\TestCase;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class DiscountManagerTest extends TestCase
{
    public function testApplyBestDiscount(): void
    {
        $strategy1 = $this->createMock(DiscountStrategyInterface::class);
        $strategy2 = $this->createMock(DiscountStrategyInterface::class);
        $cart = new Cart();

        $product1 = new ProductModel('Product 1', 50.0);
        $product2 = new ProductModel('Product 2', 50.0);
        $cart->addProduct($product1);
        $cart->addProduct($product2);

        $strategy1->method('applyDiscount')->willReturn(90.0);
        $strategy2->method('applyDiscount')->willReturn(80.0);

        $discountManager = new DiscountManager([$strategy1, $strategy2]);

        $finalPrice = $discountManager->applyBestDiscount($cart);

        $this->assertEquals(80.0, $finalPrice);
    }
}
