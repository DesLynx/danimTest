<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Query;

use App\DanimPanier\Domain\ValueObject\PanierId;
use App\Shared\Domain\Query\QueryInterface;

final readonly class FindPanierQuery implements QueryInterface
{
    public function __construct(
        public PanierId $id,
    ) {
    }
}
