<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Event;

use App\DanimPanier\Domain\Event\PanierEvent;
use App\DanimPanier\Domain\ValueObject\DiscountPercent;
use App\DanimPanier\Domain\ValueObject\DiscountValue;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\DanimPanier\Domain\ValueObject\Total;

final readonly class PanierDiscountWasUpdated implements PanierEvent
{
    public function __construct(
        private PanierId $id,
        public ?DiscountValue $discountValue = null,
        public ?DiscountPercent $discountPercent = null,
    ) {
    }

    public function id(): PanierId
    {
        return $this->id;
    }
}
