<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Projection;

use App\DanimPanier\Domain\Event\CouponWasCreated;
use App\DanimPanier\Domain\Event\CouponWasRevoked;
use App\DanimPanier\Domain\Model\Coupon;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionState;
use Ecotone\Modelling\Attribute\EventHandler;

#[Projection(self::NAME, Coupon::class)]
final class CouponIdsProjection
{
    public const NAME = 'couponList';

    /**
     * @param list<string> $couponIdsState
     *
     * @return list<string>
     */
    #[EventHandler]
    public function addCoupon(CouponWasCreated $event, #[ProjectionState] array $couponIdsState): array
    {
        $couponIdsState[] = (string) $event->id();

        return $couponIdsState;
    }

    /**
     * @param array<string> $couponIdsState
     *
     * @return list<string>
     */
    #[EventHandler]
    public function removeCoupon(CouponWasRevoked $event, #[ProjectionState] array $couponIdsState): array
    {
        if (false !== $index = array_search((string) $event->id(), $couponIdsState, true)) {
            unset($couponIdsState[$index]);
        }

        return array_values($couponIdsState);
    }
}
