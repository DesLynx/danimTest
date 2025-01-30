<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DanimPanier\Domain\Command\DiscountPanierCommand;
use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Domain\Query\FindPanierQuery;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\DanimPanier\Infrastructure\ApiPlatform\Payload\DiscountPanierPayload;
use App\DanimPanier\Infrastructure\ApiPlatform\Resource\PanierResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use Webmozart\Assert\Assert;

final readonly class DiscountPanierProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): PanierResource
    {
        Assert::isInstanceOf($data, DiscountPanierPayload::class);

        $panierResource = $context['previous_data'];
        Assert::isInstanceOf($panierResource, PanierResource::class);

        $command = new DiscountPanierCommand(
            new PanierId((string) $panierResource->id),
            new CouponId((string) $data->couponId),
        );

        $this->commandBus->dispatch($command);

        /** @var Panier $model */
        $model = $this->queryBus->ask(new FindPanierQuery($command->id));

        return PanierResource::fromModel($model);
    }
}
