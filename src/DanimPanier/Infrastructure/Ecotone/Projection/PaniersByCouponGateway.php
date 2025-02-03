<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Projection;

use Ecotone\EventSourcing\Attribute\ProjectionStateGateway;

interface PaniersByCouponGateway
{
    /**
     * @return array<string, list<string>>
     */
    #[ProjectionStateGateway(PaniersByCouponProjection::NAME)]
    public function getByCouponPanierIds(): array;
}
