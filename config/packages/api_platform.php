<?php

declare(strict_types=1);

use App\DanimPanier\Domain\Exception\CouponDeniedCauseAmountException;
use App\DanimPanier\Domain\Exception\CouponDeniedCauseExpiredException;
use App\DanimPanier\Domain\Exception\CouponDeniedCauseUsageException;
use App\DanimPanier\Domain\Exception\MissingCouponException;
use App\DanimPanier\Domain\Exception\MissingPanierException;
use App\DanimPanier\Domain\Exception\MoreThanOneDiscountTypeException;
use App\DanimPanier\Domain\Exception\NotUniqueCouponCodeException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Webmozart\Assert\InvalidArgumentException;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('api_platform', [
        'mapping' => [
            'paths' => [
                '%kernel.project_dir%/src/DanimPanier/Infrastructure/ApiPlatform/Resource/',
            ],
        ],
        'patch_formats' => [
            'json' => ['application/merge-patch+json'],
        ],
        'swagger' => [
            'versions' => [3],
        ],
        'exception_to_status' => [
            // TODO
            // We must trigger the API Platform validator before the data transforming.
            // Let's create an API Platform PR to update the AbstractItemNormalizer.
            // In that way, this exception won't be raised anymore as payload will be validated (see DiscountPanierPayload).
            CouponDeniedCauseAmountException::class => 400,
            CouponDeniedCauseExpiredException::class => 400,
            CouponDeniedCauseUsageException::class => 400,
            MissingCouponException::class => 404,
            MissingPanierException::class => 404,
            MoreThanOneDiscountTypeException::class => 422,
            NotUniqueCouponCodeException::class => 422,
            InvalidArgumentException::class => 422,
        ],
    ]);
};
