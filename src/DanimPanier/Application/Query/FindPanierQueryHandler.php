<?php

declare(strict_types=1);

namespace App\DanimPanier\Application\Query;

use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Domain\Query\FindPanierQuery;
use App\DanimPanier\Domain\Repository\PanierRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Ecotone\Modelling\Attribute\QueryHandler;

final readonly class FindPanierQueryHandler implements QueryHandlerInterface
{
    public function __construct(private PanierRepositoryInterface $repository)
    {
    }

    #[QueryHandler]
    public function __invoke(FindPanierQuery $query): ?Panier
    {
        return $this->repository->ofId($query->id);
    }
}
