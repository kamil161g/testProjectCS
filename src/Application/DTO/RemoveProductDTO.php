<?php

declare(strict_types=1);

namespace App\Application\DTO;

use Webmozart\Assert\Assert;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class RemoveProductDTO
{
    private array $productIds;

    public function __construct(array $productIds)
    {
        $this->productIds = $productIds;
    }

    public function getProductIds(): array
    {
        return $this->productIds;
    }

    public static function fromArray(array $data): self
    {
        Assert::keyExists($data, 'productIds');
        Assert::isArray($data['productIds']);
        foreach ($data['productIds'] as $id) {
            Assert::string($id);
        }

        return new self($data['productIds']);
    }
}
