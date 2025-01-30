<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Exception;

use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\PanierId;

final class CouponDeniedCauseUsageException extends \RuntimeException
{
    public function __construct(CouponId $couponId, PanierId $panierId, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Cannot apply Coupon (with id %s) to Panier (with id %s) because Coupon has already been used 10 times', (string) $couponId,  (string) $panierId), $code, $previous);
    }
}
