<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\ValueObject;

final readonly class DateTime
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
