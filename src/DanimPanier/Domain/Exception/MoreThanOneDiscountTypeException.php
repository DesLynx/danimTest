<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Exception;

use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\PanierId;

final class MoreThanOneDiscountTypeException extends \RuntimeException
{
    public function __construct(int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('A Coupon cannot have a discountValue and a discountPercent', $code, $previous);
    }
}
