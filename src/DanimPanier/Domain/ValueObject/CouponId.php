<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\ValueObject;

use App\Shared\Domain\ValueObject\AggregateRootId;

final class CouponId implements \Stringable
{
    use AggregateRootId;
}
