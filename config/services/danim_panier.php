<?php

declare(strict_types=1);

use App\DanimPanier\Domain\Repository\CouponRepositoryInterface;
use App\DanimPanier\Domain\Repository\PanierRepositoryInterface;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\CreateCouponProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\CreatePanierProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\DeletePanierProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\DiscountPanierProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\RevokeCouponProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\UpdateCouponProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Processor\UpdatePanierProcessor;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Provider\CouponCollectionProvider;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Provider\CouponItemProvider;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Provider\PanierCollectionProvider;
use App\DanimPanier\Infrastructure\ApiPlatform\State\Provider\PanierItemProvider;
use App\DanimPanier\Infrastructure\Ecotone\Repository\CouponRepository;
use App\DanimPanier\Infrastructure\Ecotone\Repository\PanierRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\DanimPanier\\', __DIR__.'/../../src/DanimPanier');

    // providers
    $services->set(PanierItemProvider::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);
    $services->set(PanierCollectionProvider::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);

    $services->set(CouponItemProvider::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);
    $services->set(CouponCollectionProvider::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);

    // processors
    $services->set(DiscountPanierProcessor::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 1]);
    $services->set(CreatePanierProcessor::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);
    $services->set(UpdatePanierProcessor::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);
    $services->set(DeletePanierProcessor::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);

    $services->set(CreateCouponProcessor::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);
    $services->set(UpdateCouponProcessor::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);
    $services->set(RevokeCouponProcessor::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_processor', ['priority' => 0]);

    // repositories
    $services->set(PanierRepositoryInterface::class)
        ->class(PanierRepository::class);
    $services->set(CouponRepositoryInterface::class)
        ->class(CouponRepository::class);
};
