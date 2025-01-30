<?php

declare(strict_types=1);

namespace App\DanimPanier\Application\Query;

use App\DanimPanier\Domain\Query\FindPaniersQuery;
use App\DanimPanier\Domain\Repository\PanierRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Ecotone\Modelling\Attribute\QueryHandler;

final readonly class FindPaniersQueryHandler implements QueryHandlerInterface
{
    public function __construct(private PanierRepositoryInterface $panierRepository)
    {
    }

    #[QueryHandler]
    public function __invoke(FindPaniersQuery $query): iterable
    {
        if (null !== $query->page && null !== $query->itemsPerPage) {
            return $this->panierRepository->paginator($query->page, $query->itemsPerPage);
        }

        return $this->panierRepository->all();
    }
}
