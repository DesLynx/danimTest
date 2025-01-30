<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\Query\FindCouponQuery;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Infrastructure\ApiPlatform\Resource\CouponResource;
use App\Shared\Application\Query\QueryBusInterface;

/**
 * @implements ProviderInterface<CouponResource>
 */
final readonly class CouponItemProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?CouponResource
    {
        /** @var string $id */
        $id = $uriVariables['id'];

        /** @var Coupon|null $model */
        $model = $this->queryBus->ask(new FindCouponQuery(new CouponId($id)));

        return null !== $model ? CouponResource::fromModel($model) : null;
    }
}
