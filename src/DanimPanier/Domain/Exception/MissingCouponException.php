<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Exception;

use App\DanimPanier\Domain\ValueObject\CouponId;

final class MissingCouponException extends \RuntimeException
{
    public function __construct(CouponId $id, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Cannot find Coupon with id %s', (string) $id), $code, $previous);
    }
}
