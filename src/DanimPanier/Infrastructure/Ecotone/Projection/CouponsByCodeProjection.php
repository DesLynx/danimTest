<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Projection;

use App\DanimPanier\Domain\Event\CouponWasCreated;
use App\DanimPanier\Domain\Event\CouponWasRevoked;
use App\DanimPanier\Domain\Event\CouponWasUpdated;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\ValueObject\Code;
use App\DanimPanier\Domain\ValueObject\CouponId;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionState;
use Ecotone\Modelling\Attribute\EventHandler;

#[Projection(self::NAME, Coupon::class)]
final class CouponsByCodeProjection
{
    public const NAME = 'couponsByCode';

    /**
     * @param array<string, list<string>> $couponsByCodeState
     *
     * @return array<string, list<string>>
     */
    #[EventHandler]
    public function addCoupon(CouponWasCreated $event, #[ProjectionState] array $couponsByCodeState): array
    {
        return $this->addCouponToCodeCoupons($event->id(), $event->code, $couponsByCodeState);
    }

    /**
     * @param array<string, list<string>> $couponsByCodeState
     *
     * @return array<string, list<string>>
     */
    #[EventHandler]
    public function updateCoupon(CouponWasUpdated $event, #[ProjectionState] array $couponsByCodeState): array
    {
        if (!$event->code) {
            return $couponsByCodeState;
        }

        $couponsByCodeState = $this->removeCouponFromCodeCoupons($event->id(), $couponsByCodeState);

        return $this->addCouponToCodeCoupons($event->id(), $event->code, $couponsByCodeState);
    }

    /**
     * @param array<string, list<string>> $couponsByCodeState
     *
     * @return array<string, list<string>>
     */
    #[EventHandler]
    public function removeCoupon(CouponWasRevoked $event, #[ProjectionState] array $couponsByCodeState): array
    {
        return $this->removeCouponFromCodeCoupons($event->id(), $couponsByCodeState);
    }

    /**
     * @param array<string, list<string>> $couponsByCodeState
     *
     * @return array<string, list<string>>
     */
    private function addCouponToCodeCoupons(CouponId $couponId, Code $code, array $couponsByCodeState): array
    {
        if (!isset($couponsByCodeState[$code->value])) {
            $couponsByCodeState[$code->value] = [];
        }

        if ($this->findCouponCode($couponId, $couponsByCodeState)?->equals($code)) {
            return $couponsByCodeState;
        }

        $couponsByCodeState[$code->value][] = (string) $couponId;

        return $couponsByCodeState;
    }

    /**
     * @param array<string, list<string>> $couponsByCodeState
     *
     * @return array<string, list<string>>
     */
    private function removeCouponFromCodeCoupons(CouponId $couponId, array $couponsByCodeState): array
    {
        $previousCode = $this->findCouponCode($couponId, $couponsByCodeState);

        if (!$previousCode) {
            return $couponsByCodeState;
        }

        $previousCouponIndex = array_search((string) $couponId, $couponsByCodeState[$previousCode->value], true);
        unset($couponsByCodeState[$previousCode->value][$previousCouponIndex]);
        $couponsByCodeState[$previousCode->value] = array_values($couponsByCodeState[$previousCode->value]);

        return $couponsByCodeState;
    }

    /**
     * @param array<string, list<string>> $couponsByCodeState
     */
    private function findCouponCode(CouponId $couponId, array $couponsByCodeState): ?Code
    {
        foreach ($couponsByCodeState as $code => $coupons) {
            if (in_array((string) $couponId, $coupons, true)) {
                return new Code($code);
            }
        }

        return null;
    }
}
