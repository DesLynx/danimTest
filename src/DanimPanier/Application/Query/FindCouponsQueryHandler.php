<?php

declare(strict_types=1);

namespace App\DanimPanier\Application\Query;

use App\DanimPanier\Domain\Query\FindCouponsQuery;
use App\DanimPanier\Domain\Repository\CouponRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Ecotone\Modelling\Attribute\QueryHandler;

final readonly class FindCouponsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private CouponRepositoryInterface $couponRepository)
    {
    }

    #[QueryHandler]
    public function __invoke(FindCouponsQuery $query): iterable
    {
        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $this->couponRepository->paginator($query->page, $query->itemsPerPage);
        }

        return $this->couponRepository->all();
    }
}
