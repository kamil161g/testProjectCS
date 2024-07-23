<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\AddProductCommand;
use App\Domain\Entity\Product;
use App\Domain\Model\ProductModel;
use App\Domain\ValueObject\Money;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class AddProductCommandHandler extends BaseCartCommandHandler
{
    public function __invoke(AddProductCommand $command): void
    {
        $productDTO = $command->getProductDTO();
        foreach ($productDTO->getProducts() as $productData) {
            $productId = $productData['productId'];
            $quantity = $productData['quantity'];
            $product = $this->productRepository->find($productId);

            if ($product instanceof Product) {
                $this->cart->addProduct(new ProductModel($product->getId(), $product->getName(),
                    new Money($product->getPrice())),
                    $quantity);
                $this->updateSession();
            }
        }
    }
}
