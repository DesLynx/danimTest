<?php

declare(strict_types=1);

namespace App\DanimPanier\Application\Query;

use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\Query\FindCouponQuery;
use App\DanimPanier\Domain\Repository\CouponRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Ecotone\Modelling\Attribute\QueryHandler;

final readonly class FindCouponQueryHandler implements QueryHandlerInterface
{
    public function __construct(private CouponRepositoryInterface $repository)
    {
    }

    #[QueryHandler]
    public function __invoke(FindCouponQuery $query): ?Coupon
    {
        return $this->repository->ofId($query->id);
    }
}
