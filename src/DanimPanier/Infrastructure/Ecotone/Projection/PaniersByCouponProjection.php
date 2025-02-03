<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Projection;

use App\DanimPanier\Domain\Event\PanierDiscountWasUpdated;
use App\DanimPanier\Domain\Event\PanierWasDeleted;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\PanierId;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionState;
use Ecotone\Modelling\Attribute\EventHandler;

#[Projection(self::NAME, [Panier::class, Coupon::class])]
final class PaniersByCouponProjection
{
    public const NAME = 'paniersByCoupon';


    /**
     * @param array<string, list<string>> $paniersByCouponState
     *
     * @return array<string, list<string>>
     */
    #[EventHandler]
    public function updatePanierDiscount(PanierDiscountWasUpdated $event, #[ProjectionState] array $paniersByCouponState): array
    {
        $paniersByCouponState = $this->removePanierFromCouponPaniers($event->id(), $paniersByCouponState);

        if (null === $event->coupon) {
            return $paniersByCouponState;
        }

        return $this->addPanierToCouponPaniers($event->id(), $event->coupon, $paniersByCouponState);
    }

    /**
     * @param array<string, list<string>> $paniersByCouponState
     *
     * @return array<string, list<string>>
     */
    #[EventHandler]
    public function removePanier(PanierWasDeleted $event, #[ProjectionState] array $paniersByCouponState): array
    {
        return $this->removePanierFromCouponPaniers($event->id(), $paniersByCouponState);
    }

    /**
     * @param array<string, list<string>> $paniersByCouponState
     *
     * @return array<string, list<string>>
     */
    private function addPanierToCouponPaniers(PanierId $panierId, Coupon $coupon, array $paniersByCouponState): array
    {
        if (!isset($paniersByCouponState[$coupon->id()->value])) {
            $paniersByCouponState[$coupon->id()->value] = [];
        }

        if ($this->findPanierCouponId($panierId, $paniersByCouponState)?->equals($coupon->id())) {
            return $paniersByCouponState;
        }

        $paniersByCouponState[$coupon->id()->value][] = (string) $panierId;

        return $paniersByCouponState;
    }

    /**
     * @param array<string, list<string>> $paniersByCouponState
     *
     * @return array<string, list<string>>
     */
    private function removePanierFromCouponPaniers(PanierId $panierId, array $paniersByCouponState): array
    {
        $previousCoupon = $this->findPanierCouponId($panierId, $paniersByCouponState);

        if (!$previousCoupon) {
            return $paniersByCouponState;
        }

        $previousPanierIndex = array_search((string) $panierId, $paniersByCouponState[$previousCoupon->value], true);
        unset($paniersByCouponState[$previousCoupon->value][$previousPanierIndex]);
        $paniersByCouponState[$previousCoupon->value] = array_values($paniersByCouponState[$previousCoupon->value]);

        return $paniersByCouponState;
    }

    /**
     * @param array<string, list<string>> $paniersByCouponState
     */
    private function findPanierCouponId(PanierId $panierId, array $paniersByCouponState): ?CouponId
    {
        foreach ($paniersByCouponState as $couponId => $paniers) {
            if (in_array((string) $panierId, $paniers, true)) {
                return new CouponId($couponId);
            }
        }

        return null;
    }
}
