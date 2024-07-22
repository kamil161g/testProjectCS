<?php

declare(strict_types=1);

namespace App\Application\CommandHandler;

use App\Application\Command\RemoveProductCommand;
use App\Domain\Entity\Product;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class RemoveProductCommandHandler extends BaseCartCommandHandler
{
    public function __invoke(RemoveProductCommand $command): void
    {
        $productDTO = $command->getProductDTO();
        foreach ($productDTO->getProductIds() as $productId) {
            $product = $this->productRepository->find($productId);
            if ($product instanceof Product) {
                $this->cart->removeProduct($productId);
                $this->updateSession();
            }
        }
    }
}
