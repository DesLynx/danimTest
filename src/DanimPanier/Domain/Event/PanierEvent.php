<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Event;

use App\DanimPanier\Domain\ValueObject\PanierId;

interface PanierEvent
{
    public function id(): PanierId;
}
