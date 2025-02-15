<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\ValueObject;

use Webmozart\Assert\Assert;

final readonly class DiscountPercent
{
    public int $percentage;

    public function __construct(int $percentage)
    {
        Assert::range($percentage, 0, 100);

        $this->percentage = $percentage;
    }
}
