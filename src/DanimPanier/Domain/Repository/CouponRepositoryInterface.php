<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Repository;

use App\DanimPanier\Domain\Event\CouponEvent;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\ValueObject\Code;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\Shared\Domain\Repository\PaginatorInterface;

interface CouponRepositoryInterface
{
    public function ofId(CouponId $id): ?Coupon;

    /** @return iterable<CouponEvent> */
    public function findEvents(CouponId $id): iterable;

    /** @return iterable<Coupon> */
    public function all(): iterable;

    /**
     * @return PaginatorInterface<Coupon>
     */
    public function paginator(int $page, int $itemsPerPage): PaginatorInterface;


    /** @return iterable<Coupon> */
    public function findByCode(Code $code): iterable;
}
