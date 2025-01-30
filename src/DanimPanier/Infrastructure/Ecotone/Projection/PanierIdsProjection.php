<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Projection;

use App\DanimPanier\Domain\Event\PanierWasCreated;
use App\DanimPanier\Domain\Event\PanierWasDeleted;
use App\DanimPanier\Domain\Model\Panier;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionState;
use Ecotone\Modelling\Attribute\EventHandler;

#[Projection(self::NAME, Panier::class)]
final class PanierIdsProjection
{
    public const NAME = 'panierList';

    /**
     * @param list<string> $panierIdsState
     *
     * @return list<string>
     */
    #[EventHandler]
    public function addPanier(PanierWasCreated $event, #[ProjectionState] array $panierIdsState): array
    {
        $panierIdsState[] = (string) $event->id();

        return $panierIdsState;
    }

    /**
     * @param array<string> $panierIdsState
     *
     * @return list<string>
     */
    #[EventHandler]
    public function removePanier(PanierWasDeleted $event, #[ProjectionState] array $panierIdsState): array
    {
        if (false !== $index = array_search((string) $event->id(), $panierIdsState, true)) {
            unset($panierIdsState[$index]);
        }

        return array_values($panierIdsState);
    }
}
