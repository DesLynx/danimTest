<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Model;

use App\DanimPanier\Domain\Command\CreatePanierCommand;
use App\DanimPanier\Domain\Command\DeletePanierCommand;
use App\DanimPanier\Domain\Command\UpdatePanierCommand;
use App\DanimPanier\Domain\Command\UpdatePanierDiscountCommand;
use App\DanimPanier\Domain\Event\CouponUsageWasDecreased;
use App\DanimPanier\Domain\Event\PanierDiscountWasUpdated;
use App\DanimPanier\Domain\Event\PanierWasCreated;
use App\DanimPanier\Domain\Event\PanierWasDeleted;
use App\DanimPanier\Domain\Event\PanierWasUpdated;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\DanimPanier\Domain\ValueObject\Total;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\WithAggregateVersioning;

/**
 * @psalm-suppress MissingConstructor
 */
#[EventSourcingAggregate]
class Panier
{
    use WithAggregateVersioning;

    #[AggregateIdentifier]
    private PanierId $id;
    private Total $total;
    private ?Coupon $coupon = null;
    private bool $deleted = false;

    #[CommandHandler]
    public static function create(CreatePanierCommand $command): array
    {
        return [
            new PanierWasCreated(
                id: new PanierId(),
                total: $command->total,
            ),
        ];
    }

    #[CommandHandler]
    public function update(UpdatePanierCommand $command): array
    {
        return [
            new PanierWasUpdated(
                id: $command->id,
                total: $command->total,
            ),
        ];
    }

    #[CommandHandler]
    public function discount(UpdatePanierDiscountCommand $command): array
    {
        return [
            new PanierDiscountWasUpdated(
                id: $command->id,
                coupon: $command->coupon,
            ),
        ];
    }

    #[CommandHandler]
    public function delete(DeletePanierCommand $command): array
    {
        $events = [
            new PanierWasDeleted($command->id)
        ];

        if ($this->coupon !== null) {
            $events[] = new CouponUsageWasDecreased(id: $this->coupon->id());
            $events[] = new PanierDiscountWasUpdated(id: $command->id, coupon: null);
        }

        return $events;
    }

    #[EventSourcingHandler]
    public function applyPanierWasCreated(PanierWasCreated $event): void
    {
        $this->id = $event->id();
        $this->total = $event->total;
    }

    #[EventSourcingHandler]
    public function applyPanierWasUpdated(PanierWasUpdated $event): void
    {
        $this->total = $event->total ?? $this->total;
    }

    #[EventSourcingHandler]
    public function applyPanierDiscountWasUpdated(PanierDiscountWasUpdated $event): void
    {
        $this->coupon = $event->coupon;
    }
    #[EventSourcingHandler]
    public function applyPanierWasDeleted(PanierWasDeleted $event): void
    {
        $this->deleted = true;
    }

    public function id(): PanierId
    {
        return $this->id;
    }

    public function total(): Total
    {
        return $this->total;
    }

    public function coupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function deleted(): bool
    {
        return $this->deleted;
    }
}
