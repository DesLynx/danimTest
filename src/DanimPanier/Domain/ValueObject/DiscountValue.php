<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\ValueObject;

use Webmozart\Assert\Assert;

final readonly class DiscountValue
{
    public int $amount;

    public function __construct(int $amount)
    {
        Assert::greaterThanEq($amount, 0);

        $this->amount = $amount;
    }
}
