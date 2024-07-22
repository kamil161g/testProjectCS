<?php

declare(strict_types=1);

namespace Tests\Application\CommandHandler;

use App\Application\Command\RemoveProductCommand;
use App\Application\CommandHandler\RemoveProductCommandHandler;
use App\Application\DTO\RemoveProductDTO;
use App\Application\Service\CartProvider;
use App\Domain\Entity\Product;
use App\Domain\Model\Cart;
use App\Domain\Model\ProductModel;
use App\Infrastructure\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class RemoveProductCommandHandlerTest extends TestCase
{
    public function testRemoveProductFromCart(): void
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $cartProvider = $this->createMock(CartProvider::class);
        $requestStack = new RequestStack();
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);
        $requestStack->push($request);

        $product = new Product();
        $product->setName('Candy');
        $product->setPrice(10.0);
        $productRepository->method('find')->willReturn($product);

        $cart = new Cart();
        $cart->addProduct(new ProductModel('Candy', 10.0));
        $cartProvider->method('getCart')->willReturn($cart);

        $handler = new RemoveProductCommandHandler($requestStack, $productRepository, $cartProvider);
        $productDTO = new RemoveProductDTO([1]);
        $command = new RemoveProductCommand($productDTO);

        $handler->__invoke($command);

        $this->assertCount(0, $cart->getProducts());
    }
}
