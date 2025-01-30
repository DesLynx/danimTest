<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Command;

use App\DanimPanier\Domain\ValueObject\PanierId;
use App\Shared\Domain\Command\CommandInterface;

final readonly class DeletePanierCommand implements CommandInterface
{
    public function __construct(
        public PanierId $id,
    ) {
    }
}
