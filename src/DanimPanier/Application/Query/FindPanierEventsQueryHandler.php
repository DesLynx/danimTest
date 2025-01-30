<?php

declare(strict_types=1);

namespace App\DanimPanier\Application\Query;

use App\DanimPanier\Domain\Query\FindPanierEventsQuery;
use App\DanimPanier\Domain\Repository\PanierRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use Ecotone\Modelling\Attribute\QueryHandler;

final readonly class FindPanierEventsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private PanierRepositoryInterface $repository)
    {
    }

    #[QueryHandler]
    public function __invoke(FindPanierEventsQuery $query): iterable
    {
        return $this->repository->findEvents($query->id);
    }
}
