<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\Ecotone\Repository;

use App\DanimPanier\Domain\Event\CouponEvent;
use App\DanimPanier\Domain\Exception\MissingCouponException;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\Repository\CouponRepositoryInterface;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Infrastructure\Ecotone\Projection\CouponIdsGateway;
use App\Shared\Domain\Repository\CallbackPaginator;
use App\Shared\Domain\Repository\PaginatorInterface;
use Ecotone\EventSourcing\EventStore;
use Ecotone\EventSourcing\Prooph\LazyProophEventStore;
use Prooph\EventStore\Metadata\MetadataMatcher;
use Prooph\EventStore\Metadata\Operator;
use Webmozart\Assert\Assert;

final readonly class CouponRepository implements CouponRepositoryInterface
{
    public function __construct(
        private EventSourcedCouponRepository $eventSourcedRepository,
        private EventStore $eventStore,
        private CouponIdsGateway $couponIdsGateway,
    ) {
    }

    public function ofId(CouponId $id): ?Coupon
    {
        if (!$eventSourcedCoupon = $this->eventSourcedRepository->findBy($id)) {
            return null;
        }

        if ($eventSourcedCoupon->deleted()) {
            return null;
        }

        return $eventSourcedCoupon;
    }

    public function findEvents(CouponId $id): iterable
    {
        $matcher = (new MetadataMatcher())
            ->withMetadataMatch(LazyProophEventStore::AGGREGATE_ID, Operator::EQUALS(), (string) $id);

        $events = $this->eventStore->load(Coupon::class, 1, null, $matcher);

        foreach ($events as $event) {
            $couponEvent = $event->getPayload();
            Assert::isInstanceOf($couponEvent, CouponEvent::class);

            yield $couponEvent;
        }
    }

    public function all(): iterable
    {
        foreach ($this->couponIdsGateway->getCouponIds() as $couponId) {
            if ($coupon = $this->ofId(new CouponId($couponId))) {
                yield $coupon;
            }
        }
    }

    public function paginator(int $page, int $itemsPerPage): PaginatorInterface
    {
        $firstResult = ($page - 1) * $itemsPerPage;
        $maxResults = $itemsPerPage;

        return new CallbackPaginator(
            array_map(static fn (string $couponId) => new CouponId($couponId), $this->couponIdsGateway->getCouponIds()),
            $firstResult,
            $maxResults,
            fn (CouponId $couponId) => $this->ofId($couponId) ?? throw new MissingCouponException($couponId),
        );
    }
}
