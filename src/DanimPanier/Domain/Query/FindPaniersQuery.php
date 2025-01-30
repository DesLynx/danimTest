<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Query;

use App\Shared\Domain\Query\QueryInterface;

final readonly class FindPaniersQuery implements QueryInterface
{
    public function __construct(
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
