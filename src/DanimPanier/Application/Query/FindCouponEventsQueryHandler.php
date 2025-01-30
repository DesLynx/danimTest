<?php

declare(strict_types=1);

namespace App\DanimPanier\Application\Query;

use App\DanimPanier\Domain\Query\FindCouponEventsQuery;
use App\DanimPanier\Domain\Repository\CouponRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Ecotone\Modelling\Attribute\QueryHandler;

final readonly class FindCouponEventsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private CouponRepositoryInterface $repository)
    {
    }

    #[QueryHandler]
    public function __invoke(FindCouponEventsQuery $query): iterable
    {
        return $this->repository->findEvents($query->id);
    }
}
