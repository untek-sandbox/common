<?php

namespace Untek\Model\DataProvider\Interfaces;

interface FilterLanguageInterface
{

    public function getLanguage(): string;

    public function setLanguage(string $language): void;

}