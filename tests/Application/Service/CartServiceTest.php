<?php
//
//declare(strict_types=1);
//
//namespace App\Tests\Application\Service;
//
//use App\Application\Service\CartService;
//use App\Domain\Model\Cart;
//use App\Domain\Model\Product;
//use App\Domain\Repository\CartRepositoryInterface;
//use PHPUnit\Framework\TestCase;
//
//class CartServiceTest extends TestCase
//{
//    public function testAddProduct(): void
//    {
//        $cartRepository = $this->createMock(CartRepositoryInterface::class);
//        $cart = new Cart();
//
//        $cartRepository->expects($this->once())
//            ->method('getCart')
//            ->willReturn($cart);
//
//        $cartRepository->expects($this->once())
//            ->method('save')
//            ->with($this->equalTo($cart));
//
//        $cartService = new CartService($cartRepository);
//        $cartService->addProduct('Test Product', 10.0);
//
//        $this->assertCount(1, $cart->getProducts());
//        $this->assertEquals(10.0, $cart->getTotalPrice());
//    }
//
//    public function testGetTotalPrice(): void
//    {
//        $cartRepository = $this->createMock(CartRepositoryInterface::class);
//        $cart = new Cart();
//        $cart->addProduct(new Product('Test Product', 10.0));
//
//        $cartRepository->expects($this->once())
//            ->method('getCart')
//            ->willReturn($cart);
//
//        $cartService = new CartService($cartRepository);
//        $this->assertEquals(10.0, $cartService->getTotalPrice());
//    }
//
//    public function testApplyDiscountForEveryFifthProduct(): void
//    {
//        $cartRepository = $this->createMock(CartRepositoryInterface::class);
//        $cart = new Cart();
//
//        for ($i = 0; $i < 5; $i++) {
//            $cart->addProduct(new Product('Test Product', 10.0));
//        }
//
//        $cartRepository->expects($this->once())
//            ->method('getCart')
//            ->willReturn($cart);
//
//        $cartService = new CartService($cartRepository);
//
//        $this->assertEquals(40.0, $cartService->getTotalPrice());
//    }
//
//    public function testApplyPercentageDiscountOnTotalPrice(): void
//    {
//        $cartRepository = $this->createMock(CartRepositoryInterface::class);
//        $cart = new Cart();
//
//        $cart->addProduct(new Product('Test Product', 150.0));
//
//        $cartRepository->expects($this->once())
//            ->method('getCart')
//            ->willReturn($cart);
//
//        $cartService = new CartService($cartRepository);
//
//        $this->assertEquals(135.0, $cartService->getTotalPrice());
//    }
//
//    public function testNonCombinationOfDiscounts(): void
//    {
//        $cartRepository = $this->createMock(CartRepositoryInterface::class);
//        $cart = new Cart();
//
//        for ($i = 0; $i < 5; $i++) {
//            $cart->addProduct(new Product('Test Product', 50.0));
//        }
//
//        $cartRepository->expects($this->once())
//            ->method('getCart')
//            ->willReturn($cart);
//
//        $cartService = new CartService($cartRepository);
//
//        $this->assertEquals(225.0, $cartService->getTotalPrice());
//    }
//}
