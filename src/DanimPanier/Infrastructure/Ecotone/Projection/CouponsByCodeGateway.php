<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Projection;

use Ecotone\EventSourcing\Attribute\ProjectionStateGateway;

interface CouponsByCodeGateway
{
    /**
     * @return array<string, list<string>>
     */
    #[ProjectionStateGateway(CouponsByCodeProjection::NAME)]
    public function getByCodeCouponIds(): array;
}
