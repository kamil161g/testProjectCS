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
use App\Domain\ValueObject\Money;
use App\Infrastructure\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class RemoveProductCommandHandlerTest extends TestCase
{
    public function testRemoveProduct(): void
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $cartProvider = $this->createMock(CartProvider::class);
        $requestStack = new RequestStack();
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);
        $requestStack->push($request);

        $product = $this->createProduct(Uuid::fromString('5a1464b6-1245-4f81-b05b-08d0f7dc7882'), 'Test1', 1000);
        $productRepository->method('find')->willReturn($product);

        $cart = new Cart();
        $cart->addProduct(new ProductModel('5a1464b6-1245-4f81-b05b-08d0f7dc7882', 'Test1', new Money(1000)), 1);
        $cartProvider->method('getCart')->willReturn($cart);

        $handler = new RemoveProductCommandHandler($requestStack, $productRepository, $cartProvider);

        $productDTO = new RemoveProductDTO([
            ['productId' => '5a1464b6-1245-4f81-b05b-08d0f7dc7882', 'quantity' => 1]
        ]);
        $command = new RemoveProductCommand($productDTO);

        $handler->__invoke($command);
        $items = $this->getCartItems($cart);

        $this->assertCount(0, $items);
    }

    public function testRemovePartialQuantity(): void
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $cartProvider = $this->createMock(CartProvider::class);
        $requestStack = new RequestStack();
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);
        $requestStack->push($request);

        $product = $this->createProduct(Uuid::fromString('5a1464b6-1245-4f81-b05b-08d0f7dc7882'), 'Test1', 1000);
        $productRepository->method('find')->willReturn($product);

        $cart = new Cart();
        $cart->addProduct(new ProductModel('5a1464b6-1245-4f81-b05b-08d0f7dc7882', 'Test1', new Money(1000)), 3);
        $cartProvider->method('getCart')->willReturn($cart);

        $handler = new RemoveProductCommandHandler($requestStack, $productRepository, $cartProvider);

        $productDTO = new RemoveProductDTO([
            ['productId' => '5a1464b6-1245-4f81-b05b-08d0f7dc7882', 'quantity' => 1]
        ]);
        $command = new RemoveProductCommand($productDTO);

        $handler->__invoke($command);
        $items = $this->getCartItems($cart);

        $this->assertCount(1, $items);
        $this->assertEquals(2, $items['5a1464b6-1245-4f81-b05b-08d0f7dc7882']->getQuantity());
    }

    private function createProduct(UuidInterface $uuid, string $name, int $price): Product
    {
        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);
        $reflection = new ReflectionClass($product);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setValue($product, $uuid);
        return $product;
    }

    private function getCartItems(Cart $cart): array
    {
        $reflection = new ReflectionClass($cart);
        return $reflection->getProperty('items')->getValue($cart);
    }
}
