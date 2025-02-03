<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Command;

use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\Shared\Domain\Command\CommandInterface;

final readonly class UpdatePanierDiscountCommand implements CommandInterface
{
    public function __construct(
        public PanierId $id,
        public ?Coupon $coupon = null,
    ) {
    }
}
