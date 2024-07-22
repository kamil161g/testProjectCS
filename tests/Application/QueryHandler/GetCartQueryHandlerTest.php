<?php

declare(strict_types=1);

namespace Tests\Application\QueryHandler;

use App\Application\Query\GetCartQuery;
use App\Application\QueryHandler\GetCartQueryHandler;
use App\Application\Service\CartProvider;
use App\Application\Service\DiscountManager;
use App\Domain\Model\Cart;
use App\Domain\Model\ProductModel;
use PHPUnit\Framework\TestCase;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class GetCartQueryHandlerTest extends TestCase
{
    public function testInvokeWithoutDiscounts(): void
    {
        $cartProvider = $this->createMock(CartProvider::class);
        $cart = new Cart();
        $cartProvider->method('getCart')->willReturn($cart);

        $discountManager = $this->createMock(DiscountManager::class);
        $discountManager->method('applyBestDiscount')->with($cart)->willReturn(100.0);

        $query = $this->createMock(GetCartQuery::class);

        $handler = new GetCartQueryHandler($cartProvider, $discountManager);

        $query->expects($this->once())->method('setView')->with([
            'products' => $cart,
            'totalPrice' => 100.0,
        ]);

        $handler->__invoke($query);
    }

    public function testInvokeWithEveryFifthProductDiscount(): void
    {
        $cartProvider = $this->createMock(CartProvider::class);
        $cart = new Cart();
        for ($i = 0; $i < 5; $i++) {
            $cart->addProduct(new ProductModel('Candy', 10.0));
        }
        $cartProvider->method('getCart')->willReturn($cart);

        $discountManager = $this->createMock(DiscountManager::class);
        $discountManager->method('applyBestDiscount')->with($cart)->willReturn(40.0);

        $query = $this->createMock(GetCartQuery::class);

        $handler = new GetCartQueryHandler($cartProvider, $discountManager);

        $query->expects($this->once())->method('setView')->with([
            'products' => $cart,
            'totalPrice' => 40.0,
        ]);

        $handler->__invoke($query);
    }

    public function testInvokeWithPercentageDiscount(): void
    {
        $cartProvider = $this->createMock(CartProvider::class);
        $cart = new Cart();
        $cart->addProduct(new ProductModel('Product 1', 110.0));
        $cartProvider->method('getCart')->willReturn($cart);

        $discountManager = $this->createMock(DiscountManager::class);
        $discountManager->method('applyBestDiscount')->with($cart)->willReturn(99.0); // 10% discount

        $query = $this->createMock(GetCartQuery::class);

        $handler = new GetCartQueryHandler($cartProvider, $discountManager);

        $query->expects($this->once())->method('setView')->with([
            'products' => $cart,
            'totalPrice' => 99.0,
        ]);

        $handler->__invoke($query);
    }

    public function testInvokeWithNonCombiningPromotions(): void
    {
        $cartProvider = $this->createMock(CartProvider::class);
        $cart = new Cart();
        for ($i = 0; $i < 5; $i++) {
            $cart->addProduct(new ProductModel('Candy', 10.0));
        }
        $cart->addProduct(new ProductModel('Expensive Item', 120.0));
        $cartProvider->method('getCart')->willReturn($cart);

        $discountManager = $this->createMock(DiscountManager::class);
        $discountManager->method('applyBestDiscount')->with($cart)->willReturn(153.0);

        $query = $this->createMock(GetCartQuery::class);

        $handler = new GetCartQueryHandler($cartProvider, $discountManager);

        $query->expects($this->once())->method('setView')->with([
            'products' => $cart,
            'totalPrice' => 153.0,
        ]);

        $handler->__invoke($query);
    }
}
