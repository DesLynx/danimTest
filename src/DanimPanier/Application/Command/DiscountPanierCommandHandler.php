<?php

declare(strict_types=1);

namespace App\DanimPanier\Application\Command;

use App\DanimPanier\Domain\Command\IncreaseCouponUsageCommand;
use App\DanimPanier\Domain\Command\DiscountPanierCommand;
use App\DanimPanier\Domain\Command\UpdatePanierDiscountCommand;
use App\DanimPanier\Domain\Exception\CouponDeniedCauseAmountException;
use App\DanimPanier\Domain\Exception\CouponDeniedCauseExpiredException;
use App\DanimPanier\Domain\Exception\CouponDeniedCauseUsageException;
use App\DanimPanier\Domain\Repository\CouponRepositoryInterface;
use App\DanimPanier\Domain\Repository\PanierRepositoryInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use Ecotone\Modelling\Attribute\CommandHandler;
use Webmozart\Assert\Assert;

final readonly class DiscountPanierCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PanierRepositoryInterface $panierRepository,
        private CouponRepositoryInterface $couponRepository,
        private CommandBusInterface $commandBus)
    {
    }

    #[CommandHandler]
    public function __invoke(DiscountPanierCommand $command): void
    {
        $panier = $this->panierRepository->ofId($command->id);
        Assert::notNull($panier);
        $coupon = $this->couponRepository->ofId($command->couponId);
        Assert::notNull($coupon);

        // checks
        if ($coupon->usageCount()->amount >= 10) {
            throw new CouponDeniedCauseUsageException(couponId: $command->couponId, panierId: $command->id);
        }
        if (new \DateTimeImmutable($coupon->createdAt()->value) < new \DateTimeImmutable('-2 months')) {
            throw new CouponDeniedCauseExpiredException(couponId: $command->couponId, panierId: $command->id);
        }
        if ($panier->total()->amount < 5000) {
            throw new CouponDeniedCauseAmountException(couponId: $command->couponId, panierId: $command->id);
        }

        $this->commandBus->dispatch(
            new UpdatePanierDiscountCommand(
                id: $command->id,
                coupon: $coupon,
            )
        );

        $this->commandBus->dispatch(
            new IncreaseCouponUsageCommand(
                id: $command->couponId,
            )
        );
    }
}
