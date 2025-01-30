<?php

namespace App\DanimPanier\Domain\Command;

use App\DanimPanier\Domain\ValueObject\CouponId;
use App\Shared\Domain\Command\CommandInterface;

final readonly class IncreaseCouponUsageCommand implements CommandInterface
{
    public function __construct(
        public CouponId $id,
    ) {
    }
}
