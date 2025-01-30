<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Domain\Query\FindPanierQuery;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\DanimPanier\Infrastructure\ApiPlatform\Resource\PanierResource;
use App\Shared\Application\Query\QueryBusInterface;

/**
 * @implements ProviderInterface<PanierResource>
 */
final readonly class PanierItemProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?PanierResource
    {
        /** @var string $id */
        $id = $uriVariables['id'];

        /** @var Panier|null $model */
        $model = $this->queryBus->ask(new FindPanierQuery(new PanierId($id)));

        return null !== $model ? PanierResource::fromModel($model) : null;
    }
}
