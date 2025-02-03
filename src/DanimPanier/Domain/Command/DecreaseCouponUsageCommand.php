<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Command;

use App\DanimPanier\Domain\ValueObject\CouponId;
use App\Shared\Domain\Command\CommandInterface;

final readonly class DecreaseCouponUsageCommand implements CommandInterface
{
    public function __construct(
        public CouponId $id,
    ) {
    }
}
