<?php

declare(strict_types=1);

namespace Tests\Application\Service;

use App\Application\Service\CartProvider;
use App\Domain\Model\Cart;
use App\Domain\Enum\SessionKey;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class CartProviderTest extends TestCase
{
    public function testGetCartFromSession(): void
    {
        $requestStack = new RequestStack();
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);
        $requestStack->push($request);

        $cart = new Cart();
        $session->set(SessionKey::Cart->value, serialize($cart));

        $cartProvider = new CartProvider($requestStack);

        $retrievedCart = $cartProvider->getCart();
        $this->assertEquals($cart, $retrievedCart);
    }

    public function testCreateNewCartIfNotInSession(): void
    {
        $requestStack = new RequestStack();
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);
        $requestStack->push($request);

        $cartProvider = new CartProvider($requestStack);

        $newCart = $cartProvider->getCart();
        $this->assertInstanceOf(Cart::class, $newCart);
    }
}
