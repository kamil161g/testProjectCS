<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\AddProductCommand;
use App\Application\Service\CartProvider;
use App\Domain\Entity\Product;
use App\Domain\Enum\SessionKey;
use App\Domain\Model\Cart;
use App\Domain\Model\ProductModel;
use App\Infrastructure\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class AddProductCommandHandler
{
    private Cart $cart;

    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository,
        private CartProvider $cartProvider
    ) {
    }

    public function __invoke(AddProductCommand $command): void
    {
        $productDTO = $command->getProductDTO();
        $this->cart = $this->cartProvider->getCart();
        foreach ($productDTO->getProductIds() as $productId) {
            $product = $this->productRepository->find($productId);
            if ($product instanceof Product) {
                $this->cart->addProduct(new ProductModel($product->getName(), $product->getPrice()));
                $this->requestStack->getSession()->set(SessionKey::Cart->value, serialize($this->cart));
            }
        }
    }
}
