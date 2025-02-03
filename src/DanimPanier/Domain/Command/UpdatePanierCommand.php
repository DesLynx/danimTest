<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Command;

use App\DanimPanier\Domain\ValueObject\PanierId;
use App\DanimPanier\Domain\ValueObject\Total;
use App\Shared\Domain\Command\CommandInterface;

final readonly class UpdatePanierCommand implements CommandInterface
{
    public function __construct(
        public PanierId $id,
        public ?Total $total = null,
    ) {
    }
}
