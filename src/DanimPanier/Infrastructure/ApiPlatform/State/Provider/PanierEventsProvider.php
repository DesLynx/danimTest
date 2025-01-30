<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\ArrayPaginator;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\PaginatorInterface;
use ApiPlatform\State\ProviderInterface;
use App\DanimPanier\Domain\Event\PanierEvent;
use App\DanimPanier\Domain\Query\FindPanierEventsQuery;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\Shared\Application\Query\QueryBusInterface;

/**
 * @implements ProviderInterface<PanierEvent>
 */
final readonly class PanierEventsProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Pagination $pagination,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): PaginatorInterface|array
    {
        /** @var string $id */
        $id = $uriVariables['id'];

        /** @var \Generator<PanierEvent> $panierEvents */
        $panierEvents = $this->queryBus->ask(new FindPanierEventsQuery(new PanierId($id)));
        $panierEvents = iterator_to_array($panierEvents);

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);

            return new ArrayPaginator($panierEvents, ($offset - 1) * $limit, $limit);
        }

        return $panierEvents;
    }
}
