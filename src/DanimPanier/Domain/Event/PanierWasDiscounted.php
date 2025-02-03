<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Event;

use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\PanierId;

final readonly class PanierWasDiscounted implements PanierEvent
{
    public function __construct(
        private PanierId $id,
        public CouponId $couponId,
    ) {
    }

    public function id(): PanierId
    {
        return $this->id;
    }
}
