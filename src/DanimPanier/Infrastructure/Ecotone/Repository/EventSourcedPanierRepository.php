<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Repository;

use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Domain\ValueObject\PanierId;
use Ecotone\Modelling\Attribute\RelatedAggregate;
use Ecotone\Modelling\Attribute\Repository;

interface EventSourcedPanierRepository
{
    #[Repository]
    public function findBy(PanierId $panierId): ?Panier;

    #[Repository]
    public function getBy(PanierId $panierId): Panier;

    #[Repository]
    #[RelatedAggregate(Panier::class)]
    public function save(PanierId $panierId, int $currentVersion, array $events): void;
}
