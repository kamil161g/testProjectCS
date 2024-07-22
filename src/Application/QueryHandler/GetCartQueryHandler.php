<?php

declare(strict_types=1);

namespace App\Application\QueryHandler;

use App\Application\Query\GetCartQuery;
use App\Application\Service\CartProvider;
use App\Application\Service\DiscountManager;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class GetCartQueryHandler
{
    public function __construct(private CartProvider $cartProvider, private DiscountManager $discountManager) {}

    public function __invoke(GetCartQuery $query): void
    {
        $cart = $this->cartProvider->getCart();
        $totalPrice = $this->discountManager->applyBestDiscount($cart);

        $query->setView([
            'products' => $cart,
            'totalPrice' => $totalPrice
        ]);
    }
}
