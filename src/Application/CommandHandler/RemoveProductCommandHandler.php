<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\RemoveProductCommand;
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
class RemoveProductCommandHandler
{
    private Cart $cart;

    public function __construct(
        private CartProvider $cartProvider,
        private ProductRepository $productRepository,
        private RequestStack $requestStack
    ) {
        $this->cart = $this->cartProvider->getCart();
    }

    public function __invoke(RemoveProductCommand $command): void
    {
        $productDTO = $command->getProductDTO();
        foreach ($productDTO->getProductIds() as $productId) {
            $product = $this->productRepository->find($productId);
            if ($product instanceof Product) {
                $this->cart->removeProduct(new ProductModel($product->getName(), $product->getPrice()));
                $this->requestStack->getSession()->set(SessionKey::Cart->value, serialize($this->cart));
            }
        }
    }
}
