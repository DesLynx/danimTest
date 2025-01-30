<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Projection;

use App\DanimPanier\Domain\Event\PanierWasCreated;
use App\DanimPanier\Domain\Event\PanierWasDeleted;
use App\DanimPanier\Domain\Event\PanierWasDiscounted;
use App\DanimPanier\Domain\Event\PanierWasUpdated;
use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Domain\ValueObject\Total;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionState;
use Ecotone\Modelling\Attribute\EventHandler;

#[Projection(self::NAME, Panier::class)]
final class PanierTotalProjection
{
    public const NAME = 'panierTotalList';

    /**
     * @param array<string, int> $panierTotalState
     *
     * @return array<string, int>
     */
    #[EventHandler]
    public function addPanier(PanierWasCreated $event, #[ProjectionState] array $panierTotalState): array
    {
        $panierTotalState[(string) $event->id()] = $event->total->amount;

        return $panierTotalState;
    }

    /**
     * @param array<string, int> $panierTotalState
     *
     * @return array<string, int>
     */
    #[EventHandler]
    public function updatePanier(PanierWasUpdated $event, #[ProjectionState] array $panierTotalState): array
    {
        if (!$event->total) {
            return $panierTotalState;
        }

        $panierTotalState[(string) $event->id()] = $event->total->amount;

        return $panierTotalState;
    }

    /**
     * @param array<string, int> $panierTotalState
     *
     * @return array<string, int>
     */
    #[EventHandler]
    public function discountPanier(PanierWasDiscounted $event, #[ProjectionState] array $panierTotalState): array
    {
        $price = new Total($panierTotalState[(string) $event->id()]);

        $panierTotalState[(string) $event->id()] = $price->applyDiscount($event->discount)->amount;

        return $panierTotalState;
    }

    /**
     * @param array<string, int> $panierTotalState
     *
     * @return array<string, int>
     */
    #[EventHandler]
    public function removePanier(PanierWasDeleted $event, #[ProjectionState] array $panierTotalState): array
    {
        unset($panierTotalState[(string) $event->id()]);

        return $panierTotalState;
    }
}
