<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Projection;

use Ecotone\EventSourcing\Attribute\ProjectionStateGateway;

interface PanierIdsGateway
{
    /**
     * @return list<string>
     */
    #[ProjectionStateGateway(PanierIdsProjection::NAME)]
    public function getPanierIds(): array;
}
