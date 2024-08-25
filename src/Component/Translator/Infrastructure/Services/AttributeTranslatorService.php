<?php

namespace Untek\Component\Translator\Infrastructure\Services;

use Illuminate\Support\Arr;
use Symfony\Contracts\Translation\TranslatorInterface;

class AttributeTranslatorService
{

    public function __construct(
        private TranslatorInterface $translator,
        private array $locales,
    )
    {
    }

    public function translate(array $value): string
    {
        $languageCode = $this->getLanguageCode();
        if (!empty($value[$languageCode])) {
            return $value[$languageCode];
        }
        foreach ($this->locales as $code) {
            if (!empty($value[$code])) {
                return $value[$code];
            }
        }
        return Arr::first($value);
    }

    private function getLanguageCode(): string
    {
        $locale = $this->translator->getLocale();
        return substr($locale, 0, 2);
    }
}