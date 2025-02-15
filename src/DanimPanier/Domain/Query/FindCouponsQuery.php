<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Query;

use App\DanimPanier\Domain\ValueObject\Code;
use App\Shared\Domain\Query\QueryInterface;

final readonly class FindCouponsQuery implements QueryInterface
{
    public function __construct(
        public ?Code $code = null,
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
