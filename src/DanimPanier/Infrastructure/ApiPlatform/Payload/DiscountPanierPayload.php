<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\Payload;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class DiscountPanierPayload
{
    public function __construct(
        #[Assert\NotNull]
        public string $couponId,
    ) {
    }
}
