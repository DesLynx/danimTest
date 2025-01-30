<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Projection;

use Ecotone\EventSourcing\Attribute\ProjectionStateGateway;

interface PanierTotalGateway
{
    /**
     * @return array<string, int>
     */
    #[ProjectionStateGateway(PanierTotalProjection::NAME)]
    public function getPanierTotalList(): array;
}
