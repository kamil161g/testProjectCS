<?php

declare(strict_types=1);

namespace Tests\Application\QueryHandler;

use App\Application\Query\GetCartQuery;
use App\Application\QueryHandler\GetCartQueryHandler;
use App\Application\Service\CartProvider;
use App\Application\Service\DiscountManager;
use App\Domain\Model\Cart;
use App\Domain\Model\ProductModel;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class GetCartQueryHandlerTest extends TestCase
{
    public function testInvokeReturnsCorrectCart(): void
    {
        $cartProvider = $this->createMock(CartProvider::class);
        $cart = new Cart();

        $money = new Money(10000);

        $cart->addProduct(new ProductModel('5a1464b6-1245-4f81-b05b-08d0f7dc788', 'Test1', $money), 1);
        $cartProvider->method('getCart')->willReturn($cart);

        $discountManager = $this->createMock(DiscountManager::class);
        $discountManager->method('applyBestDiscount')->with($cart)->willReturn($money);

        $query = $this->createMock(GetCartQuery::class);

        $handler = new GetCartQueryHandler($cartProvider, $discountManager);

        $query->expects($this->once())->method('setView')->with([
            'cart' => $cart,
            'totalPrice' => $money,
        ]);

        $handler->__invoke($query);
    }
}
