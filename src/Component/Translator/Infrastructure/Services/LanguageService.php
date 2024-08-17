<?php

namespace Untek\Component\Translator\Infrastructure\Services;

use Untek\Component\Translator\Infrastructure\Exceptions\NotFoundLanguageException;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

class LanguageService
{

    public function __construct(private array $languageCodes)
    {
    }

    /**
     * @param string $language
     * @throws NotFoundLanguageException
     */
    public function validateLanguageCode(string $language): void
    {
        if (!isset($this->languageCodes[$language])) {
            throw new NotFoundLanguageException('Language not defined in application.');
        }
    }

    /**
     * @param string $language
     * @return string
     * @throws NotFoundLanguageException
     */
    public function getLocale(string $language): string
    {
        $this->validateLanguageCode($language);
        return $this->languageCodes[$language];
    }
}