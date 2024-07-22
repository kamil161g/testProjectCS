<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\DTO\AddProductDTO;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class AddProductCommand
{
    public function __construct(private AddProductDTO $productDTO) {}

    public function getProductDTO(): AddProductDTO
    {
        return $this->productDTO;
    }
}
