<?php

namespace Untek\Component\I18Next\Services;

use Untek\Component\Translation\Interfaces\Services\TranslationServiceInterface;
use Untek\Component\I18Next\Libs\Translator;

class NullTranslationService implements TranslationServiceInterface
{
    /** @var Translator[] $translators */
    private $translators = [];

    private $language = 'en';

    private $defaultLanguage = 'en';

    private $fallbackLanguage = 'en';

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language, string $fallback = null)
    {
        $language = explode('-', $language)[0];
        $this->language = $language;
        foreach ($this->translators as $translator) {
            $translator->setLanguage($language, $fallback);
        }
        if ($fallback) {
            $this->fallbackLanguage = $fallback;
        }
    }

    public function getDefaultLanguage(): string
    {
        return $this->defaultLanguage;
    }

    public function t(string $bundleName, string $key, array $variables = [])
    {
        return $key;
    }
}
