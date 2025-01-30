<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DanimPanier\Domain\Command\DeletePanierCommand;
use App\DanimPanier\Domain\ValueObject\PanierId;
use App\DanimPanier\Infrastructure\ApiPlatform\Resource\PanierResource;
use App\Shared\Application\Command\CommandBusInterface;
use Webmozart\Assert\Assert;

final readonly class DeletePanierProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): null
    {
        Assert::isInstanceOf($data, PanierResource::class);

        $this->commandBus->dispatch(new DeletePanierCommand(new PanierId((string) $data->id)));

        return null;
    }
}
