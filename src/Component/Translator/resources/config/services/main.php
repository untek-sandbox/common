<?php

use Forecast\Map\Shared\Infrastructure\Enums\LanguageEnum;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Component\Translator\Infrastructure\Services\AttributeTranslatorService;
use Untek\Component\Translator\Infrastructure\Services\LanguageService;
use Untek\Component\Translator\Infrastructure\Subscribers\RestApiLocaleSubscriber;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autoconfigure();

    $languageCodes = LanguageEnum::all();

    $services->set(LanguageService::class, LanguageService::class)
        ->args([
            $languageCodes
        ]);

    $services->set(RestApiLocaleSubscriber::class, RestApiLocaleSubscriber::class)
        ->args([
            service(TranslatorInterface::class),
            service(LanguageService::class),
        ]);

    $services->set(AttributeTranslatorService::class, AttributeTranslatorService::class)
        ->args([
            service(TranslatorInterface::class),
            array_keys($languageCodes),
        ]);
};