<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Model;

use App\DanimPanier\Domain\Command\CreateCouponCommand;
use App\DanimPanier\Domain\Command\CreateDiscountCommand;
use App\DanimPanier\Domain\Command\DecreaseCouponUsageCommand;
use App\DanimPanier\Domain\Command\IncreaseCouponUsageCommand;
use App\DanimPanier\Domain\Command\RevokeCouponCommand;
use App\DanimPanier\Domain\Command\UpdateDiscountCommand;
use App\DanimPanier\Domain\Event\CouponUsageWasDecreased;
use App\DanimPanier\Domain\Event\CouponUsageWasIncreased;
use App\DanimPanier\Domain\Event\CouponWasCreated;
use App\DanimPanier\Domain\Event\CouponWasRevoked;
use App\DanimPanier\Domain\Event\CouponWasUpdated;
use App\DanimPanier\Domain\Exception\MoreThanOneDiscountTypeException;
use App\DanimPanier\Domain\ValueObject\Code;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\DateTime;
use App\DanimPanier\Domain\ValueObject\DiscountPercent;
use App\DanimPanier\Domain\ValueObject\DiscountValue;
use App\DanimPanier\Domain\ValueObject\UsageCount;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\WithAggregateVersioning;

/**
 * @psalm-suppress MissingConstructor
 */
#[EventSourcingAggregate]
class Coupon
{
    use WithAggregateVersioning;

    #[AggregateIdentifier]
    private CouponId $id;
    private Code $code;
    private DiscountValue $discountValue;
    private DiscountPercent $discountPercent;
    private UsageCount $usageCount;
    private DateTime $createdAt;
    private bool $revoked = false;

    #[CommandHandler]
    public static function create(CreateDiscountCommand $command): array
    {
        if (!self::isValid($command->discountValue, $command->discountPercent)) {
            throw new MoreThanOneDiscountTypeException();
        }

        return [
            new CouponWasCreated(
                id: new CouponId(),
                code: $command->code,
                discountValue: $command->discountValue,
                discountPercent: $command->discountPercent,
                createdAt: new DateTime((new \DateTimeImmutable())->format(DATE_ATOM)),
            ),
        ];
    }

    #[CommandHandler]
    public function update(UpdateDiscountCommand $command): array
    {
        if (!self::isValid($command->discountValue, $command->discountPercent)) {
            throw new MoreThanOneDiscountTypeException();
        }

        return [
            new CouponWasUpdated(
                id: $command->id,
                discountValue: $command->discountValue,
                discountPercent: $command->discountPercent,
            ),
        ];
    }

    #[CommandHandler]
    public function revoke(RevokeCouponCommand $command): array
    {
        return [new CouponWasRevoked($command->id)];
    }

    #[CommandHandler]
    public function use(IncreaseCouponUsageCommand $command): array
    {
        return [new CouponUsageWasIncreased($command->id)];
    }

    #[CommandHandler]
    public function remove(DecreaseCouponUsageCommand $command): array
    {
        return [new CouponUsageWasDecreased($command->id)];
    }

    #[EventSourcingHandler]
    public function applyCouponWasCreated(CouponWasCreated $event): void
    {
        $this->id = $event->id();
        $this->code = $event->code;
        $this->discountValue = $event->discountValue;
        $this->discountPercent = $event->discountPercent;
        $this->usageCount = new UsageCount(0);
        $this->createdAt = $event->createdAt;
    }

    #[EventSourcingHandler]
    public function applyCouponWasUpdated(CouponWasUpdated $event): void
    {
        $this->code = $event->code ?? $this->code;
        $this->discountValue = $event->discountValue ?? $this->discountValue;
        $this->discountPercent = $event->discountPercent ?? $this->discountPercent;
    }

    #[EventSourcingHandler]
    public function applyCouponWasRevoked(CouponWasRevoked $event): void
    {
        $this->revoked = true;
    }

    #[EventSourcingHandler]
    public function applyCouponUsageWasIncreased(CouponUsageWasIncreased $event): void
    {
        $this->usageCount = $this->usageCount->increment();
    }

    #[EventSourcingHandler]
    public function applyCouponUsageWasDecreased(CouponUsageWasDecreased $event): void
    {
        $this->usageCount = $this->usageCount->decrement();
    }

    public function id(): CouponId
    {
        return $this->id;
    }

    public function code(): Code
    {
        return $this->code;
    }
    public function discountValue(): DiscountValue
    {
        return $this->discountValue;
    }

    public function discountPercent(): DiscountPercent
    {
        return $this->discountPercent;
    }

    public function usageCount(): UsageCount
    {
        return $this->usageCount;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function deleted(): bool
    {
        return $this->revoked;
    }

    public static function isValid(DiscountValue $discountValue, DiscountPercent $discountPercent): bool
    {
        return !((0 !== $discountValue->amount) && (0 !== $discountPercent->percentage));
    }
}
