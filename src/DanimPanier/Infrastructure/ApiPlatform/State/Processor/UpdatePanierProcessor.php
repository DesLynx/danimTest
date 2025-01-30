<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DanimPanier\Domain\Command\UpdatePanierCommand;
use App\DanimPanier\Domain\Model\Panier;
use App\DanimPanier\Domain\Query\FindPanierQuery;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\DanimPanier\Domain\ValueObject\Total;
use App\DanimPanier\Infrastructure\ApiPlatform\Resource\PanierResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use Webmozart\Assert\Assert;

final readonly class UpdatePanierProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): PanierResource
    {
        Assert::isInstanceOf($data, PanierResource::class);
        $previous = $context['previous_data'];
        Assert::isInstanceOf($previous, PanierResource::class);

        $id = (string) $previous->id;

        $command = new UpdatePanierCommand(
            new PanierId($id),
            null !== $data->total && $previous->total !== $data->total ? new Total($data->total) : null,
        );

        $this->commandBus->dispatch($command);

        /** @var Panier $model */
        $model = $this->queryBus->ask(new FindPanierQuery(new PanierId($id)));

        return PanierResource::fromModel($model);
    }
}
