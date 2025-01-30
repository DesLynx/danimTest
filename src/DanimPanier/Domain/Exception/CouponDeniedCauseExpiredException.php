<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Exception;

use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\PanierId;

final class CouponDeniedCauseExpiredException extends \RuntimeException
{
    public function __construct(CouponId $couponId, PanierId $panierId, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Cannot apply Coupon (with id %s) to Panier (with id %s) because Coupon has been created more than 2 months ago.', (string) $couponId,  (string) $panierId), $code, $previous);
    }
}
