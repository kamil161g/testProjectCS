<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
readonly class Money
{
    public function __construct(private int $amount) {}

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function add(Money $money): Money
    {
        return new self($this->amount + $money->getAmount());
    }

    public function subtract(Money $money): Money
    {
        return new self($this->amount - $money->getAmount());
    }

    public function multiply(float $factor): Money
    {
        return new self((int) ($this->amount * $factor));
    }
}
