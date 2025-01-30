<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    if ('dev' === $containerConfigurator->env()) {
        $containerConfigurator->extension('debug', [
            'dump_destination' => 'tcp://%env(VAR_DUMPER_SERVER)%',
        ]);
    }
};
