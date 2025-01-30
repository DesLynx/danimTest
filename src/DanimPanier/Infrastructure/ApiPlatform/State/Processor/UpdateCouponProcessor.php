<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DanimPanier\Domain\Command\UpdateCouponCommand;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\Query\FindCouponQuery;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\DiscountPercent;
use App\DanimPanier\Domain\ValueObject\DiscountValue;
use App\DanimPanier\Infrastructure\ApiPlatform\Resource\CouponResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use Webmozart\Assert\Assert;

final readonly class UpdateCouponProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): CouponResource
    {
        Assert::isInstanceOf($data, CouponResource::class);
        $previous = $context['previous_data'];
        Assert::isInstanceOf($previous, CouponResource::class);

        $id = (string) $previous->id;

        $command = new UpdateCouponCommand(
            new CouponId($id),
            null !== $data->discountValue && $previous->discountValue !== $data->discountValue ? new DiscountValue($data->discountValue) : null,
            null !== $data->discountPercent && $previous->discountPercent !== $data->discountValue ? new DiscountPercent($data->discountPercent) : null,
        );

        $this->commandBus->dispatch($command);

        /** @var Coupon $model */
        $model = $this->queryBus->ask(new FindCouponQuery(new CouponId($id)));

        return CouponResource::fromModel($model);
    }
}
