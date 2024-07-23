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

        $product1 = $this->createProduct(Uuid::fromString('5a1464b6-1245-4f81-b05b-08d0f7dc7882'), 'Test1', 1000);
        $productRepository->method('find')->willReturnCallback(function ($id) use ($product1) {
            return $id === '5a1464b6-1245-4f81-b05b-08d0f7dc7882' ? $product1 : null;
        });

        $cart = new Cart();
        $cartProvider->method('getCart')->willReturn($cart);

        $handler = new AddProductCommandHandler($requestStack, $productRepository, $cartProvider);
        $productDTO = new AddProductDTO([
            ['productId' => '5a1464b6-1245-4f81-b05b-08d0f7dc7882', 'quantity' => 1]
        ]);
        $command = new AddProductCommand($productDTO);

        $handler->__invoke($command);

        $items = $this->getCartItems($cart);

        $this->assertCount(1, $items);
        $this->assertArrayHasKey('5a1464b6-1245-4f81-b05b-08d0f7dc7882', $items);
        $this->assertEquals('Test1', $items['5a1464b6-1245-4f81-b05b-08d0f7dc7882']->getProduct()->getName());
        $this->assertEquals(1000,
            $items['5a1464b6-1245-4f81-b05b-08d0f7dc7882']->getProduct()->getPrice()->getAmount());
        $this->assertEquals(1, $items['5a1464b6-1245-4f81-b05b-08d0f7dc7882']->getQuantity());
    }

    public function testAddMultipleProductsToCart(): void
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $cartProvider = $this->createMock(CartProvider::class);
        $requestStack = new RequestStack();
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);
        $requestStack->push($request);

        $product1 = $this->createProduct(Uuid::fromString('5a1464b6-1245-4f81-b05b-08d0f7dc7882'), 'Test1', 1000);
        $product2 = $this->createProduct(Uuid::fromString('5a1464b6-1245-4f81-b05b-08d0f7dc7883'), 'Test2', 2000);

        $productRepository->method('find')->willReturnCallback(function ($id) use ($product1, $product2) {
            if ($id === '5a1464b6-1245-4f81-b05b-08d0f7dc7882') {
                return $product1;
            }
            if ($id === '5a1464b6-1245-4f81-b05b-08d0f7dc7883') {
                return $product2;
            }
            return null;
        });

        $cart = new Cart();

        $cartProvider->method('getCart')->willReturn($cart);

        $handler = new AddProductCommandHandler($requestStack, $productRepository, $cartProvider);
        $productDTO = new AddProductDTO([
            ['productId' => '5a1464b6-1245-4f81-b05b-08d0f7dc7882', 'quantity' => 1],
            ['productId' => '5a1464b6-1245-4f81-b05b-08d0f7dc7883', 'quantity' => 2]
        ]);
        $command = new AddProductCommand($productDTO);

        $handler->__invoke($command);

        $items = $this->getCartItems($cart);

        $this->assertCount(2, $items);
        $this->assertArrayHasKey('5a1464b6-1245-4f81-b05b-08d0f7dc7882', $items);
        $this->assertEquals('Test1', $items['5a1464b6-1245-4f81-b05b-08d0f7dc7882']->getProduct()->getName());
        $this->assertEquals(1000,
            $items['5a1464b6-1245-4f81-b05b-08d0f7dc7882']->getProduct()->getPrice()->getAmount());
        $this->assertEquals(1, $items['5a1464b6-1245-4f81-b05b-08d0f7dc7882']->getQuantity());

        $this->assertArrayHasKey('5a1464b6-1245-4f81-b05b-08d0f7dc7883', $items);
        $this->assertEquals('Test2', $items['5a1464b6-1245-4f81-b05b-08d0f7dc7883']->getProduct()->getName());
        $this->assertEquals(2000,
            $items['5a1464b6-1245-4f81-b05b-08d0f7dc7883']->getProduct()->getPrice()->getAmount());
        $this->assertEquals(2, $items['5a1464b6-1245-4f81-b05b-08d0f7dc7883']->getQuantity());
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
