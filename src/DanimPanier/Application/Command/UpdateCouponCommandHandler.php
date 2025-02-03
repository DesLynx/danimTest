<?php

declare(strict_types=1);

namespace App\DanimPanier\Application\Command;

use App\DanimPanier\Domain\Command\UpdateCouponCommand;
use App\DanimPanier\Domain\Command\UpdateDiscountCommand;
use App\DanimPanier\Domain\Command\UpdatePanierDiscountCommand;
use App\DanimPanier\Domain\Exception\MoreThanOneDiscountTypeException;
use App\DanimPanier\Domain\Exception\NotUniqueCouponCodeException;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\Repository\CouponRepositoryInterface;
use App\DanimPanier\Domain\Repository\PanierRepositoryInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use Ecotone\Modelling\Attribute\CommandHandler;
use Webmozart\Assert\Assert;

final readonly class UpdateCouponCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private PanierRepositoryInterface $panierRepository,
        private CouponRepositoryInterface $couponRepository,
        private CommandBusInterface $commandBus)
    {
    }

    #[CommandHandler]
    public function __invoke(UpdateCouponCommand $command): void
    {
        $coupon = $this->couponRepository->ofId($command->id);
        Assert::notNull($coupon);

        foreach($this->couponRepository->findByCode($command->code) as $search) {
            if (!$search->id()->equals($coupon->id())) {
                throw new NotUniqueCouponCodeException();
            }
        }
        if (!Coupon::isValid($command->discountValue, $command->discountPercent)) {
            throw new MoreThanOneDiscountTypeException();
        }

        $this->commandBus->dispatch(
            new UpdateDiscountCommand(
                id: $coupon->id(),
                code: $command->code,
                discountValue: $command->discountValue,
                discountPercent: $command->discountPercent,
            )
        );

        $coupon = $this->couponRepository->ofId($command->id);
        $paniers = $this->panierRepository->findByCoupon($coupon);
        foreach ($paniers as $panier) {
            $this->commandBus->dispatch(
                new UpdatePanierDiscountCommand(
                    id: $panier->id(),
                    coupon: $coupon,
                )
            );
        }
    }
}
