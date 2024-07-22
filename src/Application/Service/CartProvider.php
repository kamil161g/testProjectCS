<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Enum\SessionKey;
use App\Domain\Model\Cart;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class CartProvider
{
    public function __construct(private RequestStack $requestStack) {}

    public function getCart(): Cart
    {
        $session = $this->requestStack->getSession();
        $cartData = $session->get(SessionKey::Cart->value);

        if ($cartData) {
            $cart = unserialize($cartData, [Cart::class]);
        } else {
            $cart = new Cart();
        }

        return $cart;
    }
}
