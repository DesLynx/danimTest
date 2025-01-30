<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DanimPanier\Domain\Command\RevokeCouponCommand;
use App\DanimPanier\Domain\ValueObject\CouponId;
use App\DanimPanier\Infrastructure\ApiPlatform\Resource\CouponResource;
use App\Shared\Application\Command\CommandBusInterface;
use Webmozart\Assert\Assert;

final readonly class RevokeCouponProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): null
    {
        Assert::isInstanceOf($data, CouponResource::class);

        $this->commandBus->dispatch(new RevokeCouponCommand(new CouponId((string) $data->id)));

        return null;
    }
}
