<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\PaginatorInterface as ApiPlatformPaginatorInterface;
use ApiPlatform\State\ProviderInterface;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\Query\FindCouponsQuery;
use App\DanimPanier\Infrastructure\ApiPlatform\Resource\CouponResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Repository\PaginatorInterface;
use App\Shared\Infrastructure\ApiPlatform\State\Paginator;

/**
 * @implements ProviderInterface<CouponResource>
 */
final readonly class CouponCollectionProvider implements ProviderInterface
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

        /** @var iterable<Coupon>|PaginatorInterface<Coupon> $coupons */
        $coupons = $this->queryBus->ask(new FindCouponsQuery($offset, $limit));

        $resources = [];
        foreach ($coupons as $coupon) {
            $resources[] = CouponResource::fromModel($coupon);
        }

        if (null !== $offset && null !== $limit && $coupons instanceof PaginatorInterface) {
            return new Paginator(
                new \ArrayIterator($resources),
                (float) $coupons->getCurrentPage(),
                (float) $coupons->getItemsPerPage(),
                (float) $coupons->getLastPage(),
                (float) $coupons->getTotalItems(),
            );
        }

        return $resources;
    }
}
