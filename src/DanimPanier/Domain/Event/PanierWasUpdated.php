<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Event;

use App\DanimPanier\Domain\ValueObject\PanierId;
use App\DanimPanier\Domain\ValueObject\Total;

final readonly class PanierWasUpdated implements PanierEvent
{
    public function __construct(
        private PanierId        $id,
        public ?Total           $total = null,
    ) {
    }

    public function id(): PanierId
    {
        return $this->id;
    }
}
