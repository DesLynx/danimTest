<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Command;

use App\DanimPanier\Domain\ValueObject\DiscountPercent;
use App\DanimPanier\Domain\ValueObject\DiscountValue;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\Shared\Domain\Command\CommandInterface;

final readonly class UpdatePanierDiscountCommand implements CommandInterface
{
    public function __construct(
        public PanierId $id,
        public ?DiscountValue $discountValue = null,
        public ?DiscountPercent $discountPercent = null,
    ) {
    }
}
