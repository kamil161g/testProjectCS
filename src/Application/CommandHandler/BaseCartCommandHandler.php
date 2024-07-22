<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Service\CartProvider;
use App\Domain\Enum\SessionKey;
use App\Domain\Model\Cart;
use App\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
abstract class BaseCartCommandHandler
{
    protected Cart $cart;

    public function __construct(
        protected RequestStack $requestStack,
        protected ProductRepositoryInterface $productRepository,
        protected CartProvider $cartProvider
    ) {
        $this->cart = $this->cartProvider->getCart();
    }

    protected function updateSession(): void
    {
        $this->requestStack->getSession()->set(SessionKey::Cart->value, serialize($this->cart));
    }
}
