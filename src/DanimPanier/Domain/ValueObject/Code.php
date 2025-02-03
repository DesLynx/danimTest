<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\ValueObject;

final readonly class Code
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function equals(Code $code): bool
    {
        return $this->value === $code->value;
    }


}
