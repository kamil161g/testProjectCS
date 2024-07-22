<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\DTO\RemoveProductDTO;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class RemoveProductCommand
{
    public function __construct(private RemoveProductDTO $productDTO) {}

    public function getProductDTO(): RemoveProductDTO
    {
        return $this->productDTO;
    }
}
