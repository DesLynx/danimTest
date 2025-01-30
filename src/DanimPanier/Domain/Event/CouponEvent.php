<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Event;

use App\DanimPanier\Domain\ValueObject\CouponId;

interface CouponEvent
{
    public function id(): CouponId;
}
