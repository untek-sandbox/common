<?php

namespace Untek\Component\Translator\Infrastructure\Helpers;

use Exception;
use Forecast\Map\Shared\Infrastructure\Enums\LanguageEnum;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Translation\Translator;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

class TranslationLoaderHelper
{

    public static function addDirectory(Translator $translator, string $directory): void
    {
        $finder = new Finder();
        $finder->files()->in($directory);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                /** @var SplFileInfo $file */
                self::addResource($translator, $file->getRealPath());
            }
        }
    }

    public static function addResource(Translator $translator, string $file): void
    {
        list($domain, $lang, $ext) = self::parseFileName($file);
        $langToCodeAssoc = LanguageEnum::all();
        if (!isset($langToCodeAssoc[$lang])) {
            throw new Exception('Not found language for resource.');
        }
        $locale = $langToCodeAssoc[$lang];
        $translator->addResource($ext, $file, $locale, $domain);
    }

    private static function parseFileName(string $file): array
    {
        $basename = basename($file);
        $sectors = explode('.', $basename);
        $ext = self::breakOff($sectors);
        $lang = self::breakOff($sectors);
        $domain = implode('.', $sectors);
        if (empty($domain)) {
            $domain = null;
        }
        return [$domain, $lang, $ext];
    }

    private static function breakOff(array &$sectors): mixed
    {
        if (empty($sectors)) {
            return null;
        }
        $value = $sectors[count($sectors) - 1];
        array_pop($sectors);
        return $value;
    }
}