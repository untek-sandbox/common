<?php

namespace Untek\Component\Translator\Infrastructure\DependencyInjection;

use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;
use Untek\Core\Code\Helpers\DeprecateHelper;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

class TranslatorFactory
{

    public static function create(string $configFile): Translator
    {
        DeprecateHelper::hardThrow();
        $defaultLanguage = getenv('TRANSLATOR_DEFAULT_LANGUAGE');
        if (getenv('APP_ENV') === 'prod') {
            $cacheDirectory = getenv('TRANSLATOR_CACHE_DIRECTORY');
        } else {
            $cacheDirectory = null;
        }
        $translator = new Translator($defaultLanguage, null, $cacheDirectory, getenv('APP_DEBUG'));
        $translator->addLoader('php', new PhpFileLoader());
        $translator->addLoader('xlf', new XliffFileLoader());
        $translator->setFallbackLocales([$defaultLanguage]);
        $function = require $configFile;
        call_user_func($function, $translator);
        return $translator;
    }
}
