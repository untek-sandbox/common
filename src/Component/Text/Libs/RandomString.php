<?php

namespace Untek\Component\Text\Libs;

use Symfony\Component\String\ByteString;

class RandomString
{

    const NUMBER = '0123456789';
    const LOWER = 'abcdefghijklmnopqrstuvwxyz';
    const UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const SPEC_CHAR = '!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~';

    private $length = 0;
    private $characters = '';
    private $characterSets = [];

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    public function getCharacters(): string
    {
        return $this->characters;
    }

    public function setCharacters(string $characters): void
    {
        $this->characters = $characters;
    }

    public function addCharactersNumber(): void
    {
        $this->characters .= self::NUMBER;
    }

    public function addCharactersLower(): void
    {
        $this->characters .= self::LOWER;
    }

    public function addCharactersUpper(): void
    {
        $this->characters .= self::UPPER;
    }

    public function addCharactersSpecChar(): void
    {
        $this->characters .= self::SPEC_CHAR;
    }

    public function addCustomChar(string $chars): void
    {
        $this->characters .= $chars;
    }

    public function addCharactersAll(): void
    {
        $this->addCharactersNumber();
        $this->addCharactersLower();
        $this->addCharactersUpper();
        $this->addCharactersSpecChar();
    }

    public static function generateNumLowerUpper(int $length): string
    {
        $random = new RandomString();
        $random->addCharactersNumber();
        $random->addCharactersLower();
        $random->addCharactersUpper();
        $random->setLength($length);
        return $random->generateString();
    }

    public function generateString(): string
    {
        $this->validate();
        return ByteString::fromRandom($this->getLength(), $this->getCharacters())->toString();
    }

    protected function validate(): void
    {
        if ($this->length == 0) {
            throw new \Exception('Length is 0');
        }
        if (empty($this->characters)) {
            throw new \Exception('Empty characters');
        }
    }
}
