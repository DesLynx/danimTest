<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Command;

use App\DanimPanier\Domain\ValueObject\DiscountPercent;
use App\DanimPanier\Domain\ValueObject\DiscountValue;
use App\Shared\Domain\Command\CommandInterface;

final readonly class CreateCouponCommand implements CommandInterface
{
    public function __construct(
        public DiscountValue $discountValue,
        public DiscountPercent $discountPercent,
    ) {
    }
}
