<?php

declare(strict_types=1);

namespace App\DanimPanier\Domain\Exception;

use App\DanimPanier\Domain\ValueObject\PanierId;

final class MissingPanierException extends \RuntimeException
{
    public function __construct(PanierId $id, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Cannot find panier with id %s', (string) $id), $code, $previous);
    }
}
