<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\ValueObject;

use Webmozart\Assert\Assert;

final readonly class UsageCount
{
    public int $amount;

    public function __construct(int $amount)
    {
        Assert::greaterThanEq($amount, 0);

        $this->amount = $amount;
    }

    public function increment(): self
    {
        return new self($this->amount + 1);
    }

    public function decrement(): self
    {
        Assert::greaterThanEq($this->amount, 1);
        return new self($this->amount - 1);
    }
}
