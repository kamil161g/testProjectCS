<?php

declare(strict_types=1);

namespace Tests\Application\CommandHandler;

use App\Application\Command\AddProductCommand;
use App\Application\CommandHandler\AddProductCommandHandler;
use App\Application\DTO\AddProductDTO;
use App\Application\Service\CartProvider;
use App\Domain\Entity\Product;
use App\Domain\Model\Cart;
use App\Infrastructure\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class AddProductCommandHandlerTest extends TestCase
{
    public function testAddProductToCart(): void
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
        $cartProvider->method('getCart')->willReturn($cart);

        $handler = new AddProductCommandHandler($requestStack, $productRepository, $cartProvider);
        $productDTO = new AddProductDTO([1]);
        $command = new AddProductCommand($productDTO);

        $handler->__invoke($command);
        $products = $cart->getProducts();

        $this->assertCount(1, $cart->getProducts());
        $this->assertEquals('Candy', $products[0]->getName());
        $this->assertEquals(10.0, $products[0]->getPrice());
    }
}
