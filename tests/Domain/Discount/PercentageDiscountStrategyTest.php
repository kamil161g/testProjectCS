<?php

declare(strict_types=1);

namespace Tests\Domain\Discount;

use App\Domain\Discount\PercentageDiscountStrategy;
use App\Domain\Model\Cart;
use App\Domain\Model\ProductModel;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class PercentageDiscountStrategyTest extends TestCase
{
    public function testApplyDiscountWithTotalBelowThreshold(): void
    {
        $cart = new Cart();
        $product = new ProductModel('product-1', 'Product 1', new Money(5000));
        $cart->addProduct($product, 1);

        $strategy = new PercentageDiscountStrategy();
        $finalPrice = $strategy->applyDiscount($cart);

        $this->assertEquals(new Money(5000), $finalPrice);
    }

    public function testApplyDiscountWithTotalAboveThreshold(): void
    {
        $cart = new Cart();
        $product1 = new ProductModel('product-1', 'Product 1', new Money(6000));
        $product2 = new ProductModel('product-2', 'Product 2', new Money(5000));
        $cart->addProduct($product1, 1);
        $cart->addProduct($product2, 1);

        $strategy = new PercentageDiscountStrategy();
        $finalPrice = $strategy->applyDiscount($cart);

        $this->assertEquals(new Money(9900), $finalPrice);
    }
}
