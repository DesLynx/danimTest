<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Event;

use App\DanimPanier\Domain\ValueObject\Code;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\DateTime;
use App\DanimPanier\Domain\ValueObject\DiscountPercent;
use App\DanimPanier\Domain\ValueObject\DiscountValue;

final readonly class CouponWasCreated implements CouponEvent
{
    public function __construct(
        private CouponId $id,
        public Code $code,
        public DiscountValue $discountValue,
        public DiscountPercent $discountPercent,
        public DateTime $createdAt,
    ) {
    }

    public function id(): CouponId
    {
        return $this->id;
    }
}
