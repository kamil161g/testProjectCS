<?php

declare(strict_types=1);

namespace Tests\Domain\Discount;

use App\Domain\Discount\PercentageDiscountStrategy;
use App\Domain\Model\Cart;
use App\Domain\Model\ProductModel;
use PHPUnit\Framework\TestCase;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class PercentageDiscountStrategyTest extends TestCase
{
    public function testApplyDiscountWithTotalBelowThreshold(): void
    {
        $cart = new Cart();
        $product = new ProductModel('Product 1', 50.0);
        $cart->addProduct($product);

        $strategy = new PercentageDiscountStrategy();
        $finalPrice = $strategy->applyDiscount($cart);

        $this->assertEquals(50.0, $finalPrice);
    }

    public function testApplyDiscountWithTotalAboveThreshold(): void
    {
        $cart = new Cart();
        $product1 = new ProductModel('Product 1', 60.0);
        $product2 = new ProductModel('Product 2', 50.0);
        $cart->addProduct($product1);
        $cart->addProduct($product2);

        $strategy = new PercentageDiscountStrategy();
        $finalPrice = $strategy->applyDiscount($cart);

        $this->assertEquals(99.0, $finalPrice);
    }

    public function testApplyDiscountWithTotalExactlyAtThreshold(): void
    {
        $cart = new Cart();
        $product = new ProductModel('Product 1', 100.0);
        $cart->addProduct($product);

        $strategy = new PercentageDiscountStrategy();
        $finalPrice = $strategy->applyDiscount($cart);

        $this->assertEquals(100.0, $finalPrice);
    }
}
