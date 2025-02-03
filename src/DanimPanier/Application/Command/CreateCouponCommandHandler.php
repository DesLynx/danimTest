<?php

declare(strict_types=1);

namespace App\DanimPanier\Application\Command;

use App\DanimPanier\Domain\Command\CreateCouponCommand;
use App\DanimPanier\Domain\Command\CreateDiscountCommand;
use App\DanimPanier\Domain\Exception\MoreThanOneDiscountTypeException;
use App\DanimPanier\Domain\Exception\NotUniqueCouponCodeException;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\Repository\CouponRepositoryInterface;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use Ecotone\Modelling\Attribute\CommandHandler;

final readonly class CreateCouponCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CouponRepositoryInterface $couponRepository,
        private CommandBusInterface $commandBus,
    ) {
    }

    #[CommandHandler]
    public function __invoke(CreateCouponCommand $command): string
    {
        if (iterator_count($this->couponRepository->findByCode($command->code)) !== 0) {
            throw new NotUniqueCouponCodeException();
        }
        if (!Coupon::isValid($command->discountValue, $command->discountPercent)) {
            throw new MoreThanOneDiscountTypeException();
        }

        return $this->commandBus->dispatch(
            new CreateDiscountCommand(
                code: $command->code,
                discountValue: $command->discountValue,
                discountPercent: $command->discountPercent,
            )
        );
    }
}
