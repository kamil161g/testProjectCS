<?php

declare(strict_types=1);

namespace App\Domain\Model;

readonly class ProductModel
{
    public function __construct(private string $id, private string $name, private float $price) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
