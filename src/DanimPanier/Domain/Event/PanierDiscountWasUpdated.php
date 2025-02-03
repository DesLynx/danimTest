<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Event;

use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\ValueObject\PanierId;

final readonly class PanierDiscountWasUpdated implements PanierEvent
{
    public function __construct(
        private PanierId $id,
        public ?Coupon $coupon = null,
    ) {
    }

    public function id(): PanierId
    {
        return $this->id;
    }
}
