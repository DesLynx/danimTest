<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Repository;

use App\DanimPanier\Domain\Event\PanierEvent;
use App\DanimPanier\Domain\Exception\MissingPanierException;
use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Domain\Repository\PanierRepositoryInterface;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\DanimPanier\Infrastructure\Ecotone\Projection\PanierIdsGateway;
use App\Shared\Domain\Repository\CallbackPaginator;
use App\Shared\Domain\Repository\PaginatorInterface;
use Ecotone\EventSourcing\EventStore;
use Ecotone\EventSourcing\Prooph\LazyProophEventStore;
use Prooph\EventStore\Metadata\MetadataMatcher;
use Prooph\EventStore\Metadata\Operator;
use Webmozart\Assert\Assert;

final readonly class PanierRepository implements PanierRepositoryInterface
{
    public function __construct(
        private EventSourcedPanierRepository $eventSourcedRepository,
        private EventStore                   $eventStore,
        private PanierIdsGateway             $panierIdsGateway,
    ) {
    }

    public function ofId(PanierId $id): ?Panier
    {
        if (!$eventSourcedPanier = $this->eventSourcedRepository->findBy($id)) {
            return null;
        }

        if ($eventSourcedPanier->deleted()) {
            return null;
        }

        return $eventSourcedPanier;
    }

    public function findEvents(PanierId $id): iterable
    {
        $matcher = (new MetadataMatcher())
            ->withMetadataMatch(LazyProophEventStore::AGGREGATE_ID, Operator::EQUALS(), (string) $id);

        $events = $this->eventStore->load(Panier::class, 1, null, $matcher);

        foreach ($events as $event) {
            $panierEvent = $event->getPayload();
            Assert::isInstanceOf($panierEvent, PanierEvent::class);

            yield $panierEvent;
        }
    }

    public function all(): iterable
    {
        foreach ($this->panierIdsGateway->getPanierIds() as $panierId) {
            if ($panier = $this->ofId(new PanierId($panierId))) {
                yield $panier;
            }
        }
    }

    public function paginator(int $page, int $itemsPerPage): PaginatorInterface
    {
        $firstResult = ($page - 1) * $itemsPerPage;
        $maxResults = $itemsPerPage;

        return new CallbackPaginator(
            array_map(static fn (string $panierId) => new PanierId($panierId), $this->panierIdsGateway->getPanierIds()),
            $firstResult,
            $maxResults,
            fn (PanierId $panierId) => $this->ofId($panierId) ?? throw new MissingPanierException($panierId),
        );
    }
}
