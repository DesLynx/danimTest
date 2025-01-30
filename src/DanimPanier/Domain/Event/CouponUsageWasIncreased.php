<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Event;

use App\DanimPanier\Domain\ValueObject\CouponId;

final readonly class CouponUsageWasIncreased implements CouponEvent
{
    public function __construct(
        private CouponId $id,
    ) {
    }

    public function id(): CouponId
    {
        return $this->id;
    }
}
