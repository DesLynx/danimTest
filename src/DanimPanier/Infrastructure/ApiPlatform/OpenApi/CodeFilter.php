<?php

declare(strict_types=1);

namespace App\DanimPanier\Infrastructure\ApiPlatform\OpenApi;

use ApiPlatform\Api\FilterInterface;
use Symfony\Component\PropertyInfo\Type;

final class CodeFilter implements FilterInterface
{
    public function getDescription(string $resourceClass): array
    {
        return [
            'code' => [
                'property' => 'code',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
            ],
        ];
    }
}
