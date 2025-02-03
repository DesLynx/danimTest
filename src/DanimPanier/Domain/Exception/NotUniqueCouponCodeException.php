<?php

namespace App\DanimPanier\Domain\Exception;

final class NotUniqueCouponCodeException extends \RuntimeException
{
    public function __construct(int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('A Coupon already has this unique Code', $code, $previous);
    }
}
