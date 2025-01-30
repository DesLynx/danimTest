<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Projection;

use Ecotone\EventSourcing\Attribute\ProjectionStateGateway;

interface CouponIdsGateway
{
    /**
     * @return list<string>
     */
    #[ProjectionStateGateway(CouponIdsProjection::NAME)]
    public function getCouponIds(): array;
}
