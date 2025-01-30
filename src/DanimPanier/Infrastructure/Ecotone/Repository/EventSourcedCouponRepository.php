<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Repository;

use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\ValueObject\CouponId;
use Ecotone\Modelling\Attribute\RelatedAggregate;
use Ecotone\Modelling\Attribute\Repository;

interface EventSourcedCouponRepository
{
    #[Repository]
    public function findBy(CouponId $couponId): ?Coupon;

    #[Repository]
    public function getBy(CouponId $couponId): Coupon;

    #[Repository]
    #[RelatedAggregate(Coupon::class)]
    public function save(CouponId $couponId, int $currentVersion, array $events): void;
}
