<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Query;

use App\DanimPanier\Domain\ValueObject\CouponId;
use App\Shared\Domain\Query\QueryInterface;

final readonly class FindCouponEventsQuery implements QueryInterface
{
    public function __construct(
        public CouponId $id,
    ) {
    }
}
