<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\ArrayPaginator;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\PaginatorInterface;
use ApiPlatform\State\ProviderInterface;
use App\DanimPanier\Domain\Event\CouponEvent;
use App\DanimPanier\Domain\Query\FindCouponEventsQuery;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\Shared\Application\Query\QueryBusInterface;

/**
 * @implements ProviderInterface<CouponEvent>
 */
final readonly class CouponEventsProvider implements ProviderInterface
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

        /** @var \Generator<CouponEvent> $couponEvents */
        $couponEvents = $this->queryBus->ask(new FindCouponEventsQuery(new CouponId($id)));
        $couponEvents = iterator_to_array($couponEvents);

        if ($this->pagination->isEnabled($operation, $context)) {
            $offset = $this->pagination->getPage($context);
            $limit = $this->pagination->getLimit($operation, $context);

            return new ArrayPaginator($couponEvents, ($offset - 1) * $limit, $limit);
        }

        return $couponEvents;
    }
}
