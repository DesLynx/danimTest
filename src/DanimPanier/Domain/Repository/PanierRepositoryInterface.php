<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Repository;

use App\DanimPanier\Domain\Event\PanierEvent;
use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\Shared\Domain\Repository\PaginatorInterface;

interface PanierRepositoryInterface
{
    public function ofId(PanierId $id): ?Panier;

    /** @return iterable<PanierEvent> */
    public function findEvents(PanierId $id): iterable;

    /** @return iterable<Panier> */
    public function all(): iterable;

    /**
     * @return PaginatorInterface<Panier>
     */
    public function paginator(int $page, int $itemsPerPage): PaginatorInterface;

}
