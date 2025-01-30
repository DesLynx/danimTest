<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DanimPanier\Domain\Command\CreateCouponCommand;
use App\DanimPanier\Domain\Model\Coupon;
use App\DanimPanier\Domain\Query\FindCouponQuery;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\DiscountPercent;
use App\DanimPanier\Domain\ValueObject\DiscountValue;
use App\DanimPanier\Infrastructure\ApiPlatform\Resource\CouponResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use Webmozart\Assert\Assert;

final readonly class CreateCouponProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): CouponResource
    {
        Assert::isInstanceOf($data, CouponResource::class);

        Assert::notNull($data->discountValue);
        Assert::greaterThanEq($data->discountValue, 0);
        Assert::notNull($data->discountPercent);
        Assert::range($data->discountPercent, 0, 100);

        $command = new CreateCouponCommand(
            new DiscountValue($data->discountValue),
            new DiscountPercent($data->discountPercent),
        );

        /** @var string $id */
        $id = $this->commandBus->dispatch($command);

        /** @var Coupon $model */
        $model = $this->queryBus->ask(new FindCouponQuery(new CouponId($id)));

        return CouponResource::fromModel($model);
    }
}
