<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Event;

use App\DanimPanier\Domain\ValueObject\Code;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\DiscountPercent;
use App\DanimPanier\Domain\ValueObject\DiscountValue;

final readonly class CouponWasUpdated implements CouponEvent
{
    public function __construct(
        private CouponId $id,
        public ?Code $code = null,
        public ?DiscountValue $discountValue = null,
        public ?DiscountPercent $discountPercent = null,
    ) {
    }

    public function id(): CouponId
    {
        return $this->id;
    }
}
