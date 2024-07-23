<?php

declare(strict_types=1);

namespace Tests\Application\Service;

use App\Application\Service\DiscountManager;
use App\Domain\Model\Cart;
use App\Domain\Discount\DiscountStrategyInterface;
use App\Domain\Model\ProductModel;
use App\Domain\ValueObject\Money;
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

        $product1 = new ProductModel('product-1', 'Product 1', new Money(10000));
        $product2 = new ProductModel('product-2', 'Product 2', new Money(15000));
        $cart->addProduct($product1, 1);
        $cart->addProduct($product2, 1);

        $strategy1->method('applyDiscount')->willReturn(new Money(10000));
        $strategy2->method('applyDiscount')->willReturn(new Money(13500));

        $discountManager = new DiscountManager([$strategy1, $strategy2]);

        $finalPrice = $discountManager->applyBestDiscount($cart);

        $this->assertEquals(new Money(10000), $finalPrice);
    }
}
