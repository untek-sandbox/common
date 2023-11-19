<?php

namespace Untek\Component\I18Next\Facades;

use Untek\Component\I18Next\Services\NullTranslationService;
use Untek\Core\Container\Traits\ContainerAwareStaticAttributeTrait;
use Untek\Component\Translation\Interfaces\Services\TranslationServiceInterface;

class I18Next
{

    use ContainerAwareStaticAttributeTrait;

    private static $service;

    public static function getService(): TranslationServiceInterface
    {
        if (!isset(self::$service)) {
            if(self::getContainer()) {
                self::setService(self::getContainer()->get(TranslationServiceInterface::class));
            } else {
                return new NullTranslationService();
            }
        }
        return self::$service;
    }

    public static function setService(TranslationServiceInterface $translationService)
    {
        self::$service = $translationService;
    }

    public static function t(string $bundleName, string $key, array $variables = [])
    {
        $translationService = self::getService();
        return $translationService->t($bundleName, $key, $variables);
    }

    public static function translateFromArray(array $bundleName, string $key = null, array $variables = [])
    {
        $translationService = self::getService();
        return call_user_func_array([$translationService, 't'], $bundleName);
    }
}
