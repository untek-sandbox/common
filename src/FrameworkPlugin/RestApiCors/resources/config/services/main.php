<?php

use Fruitcake\Cors\CorsService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\FrameworkPlugin\RestApiCors\Infrastructure\Subscribers\CorsSubscriber;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $options = [];
    if (getenv('CORS_ALLOW_ORIGINS')) {
        $options['allowedOrigins'] = explode(',', getenv('CORS_ALLOW_ORIGINS'));
    }
    if (getenv('CORS_MAX_AGE')) {
        $options['maxAge'] = (int)getenv('CORS_MAX_AGE');
    }
    if (getenv('CORS_ALLOW_HEADERS')) {
        $options['allowedHeaders'] = explode(',', getenv('CORS_ALLOW_HEADERS'));
    }
    if (getenv('CORS_ALLOW_METHODS')) {
        $options['allowedMethods'] = explode(',', getenv('CORS_ALLOW_METHODS'));
    } else {
        $options['allowedMethods'] = ['POST'];
    }
    if (getenv('CORS_SUPPORTS_CREDENTIALS')) {
        $options['supportsCredentials'] = true;
    }
    if (getenv('CORS_EXPOSED_HEADERS')) {
        $options['exposedHeaders'] = explode(',', getenv('CORS_EXPOSED_HEADERS'));
    }

    $services->set(CorsService::class, CorsService::class)
        ->args([
            $options,
        ]);

    $services->set(CorsSubscriber::class, CorsSubscriber::class)
        ->args([
            service(CorsService::class),
        ]);
};