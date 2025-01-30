<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\PaginatorInterface as ApiPlatformPaginatorInterface;
use ApiPlatform\State\ProviderInterface;
use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Domain\Query\FindPaniersQuery;
use App\DanimPanier\Infrastructure\ApiPlatform\Resource\PanierResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Repository\PaginatorInterface;
use App\Shared\Infrastructure\ApiPlatform\State\Paginator;

/**
 * @implements ProviderInterface<PanierResource>
 */
final readonly class PanierCollectionProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ApiPlatformPaginatorInterface|array
    {
        $offset = $limit = null;

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);
        }

        /** @var iterable<Panier>|PaginatorInterface<Panier> $paniers */
        $paniers = $this->queryBus->ask(new FindPaniersQuery( $offset, $limit));

        $resources = [];
        foreach ($paniers as $panier) {
            $resources[] = PanierResource::fromModel($panier);
        }

        if (null !== $offset && null !== $limit && $paniers instanceof PaginatorInterface) {
            return new Paginator(
                new \ArrayIterator($resources),
                (float) $paniers->getCurrentPage(),
                (float) $paniers->getItemsPerPage(),
                (float) $paniers->getLastPage(),
                (float) $paniers->getTotalItems(),
            );
        }

        return $resources;
    }
}
