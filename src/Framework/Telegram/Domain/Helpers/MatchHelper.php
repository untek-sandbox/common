<?php

namespace Untek\Framework\Telegram\Domain\Helpers;


use Untek\Core\Text\Helpers\TextHelper;

class MatchHelper
{

    public static function isLogin(string $value) {
        //return $value[0] == '@';
        return preg_match('/@[\d\w]+/i', $value);
    }

    public static function isMatchTextContains(string $text, string $needle): bool
    {
        $needleArray = self::stringToArray($needle);
        return self::matchArray($text, $needleArray);
    }

    public static function isMatchText(string $text, string $needle): bool
    {
        $needleArray = [
            $needle
        ];
        return self::matchArray($text, $needleArray);
    }

    public static function prepareString(string $text): string
    {
        $text = mb_strtolower($text);
        $text = TextHelper::removeDoubleSpace($text);
        $text = trim($text);
        return $text;
    }

    private static function matchArray(string $text, array $needleArray) {
        $text = self::prepareString($text);
        foreach ($needleArray as $needleItem) {
            $isFound = self::matchItem($text, $needleItem);
            if( ! $isFound) {
                return false;
            }
        }
        return true;
    }

    private static function matchItem(string $text, string $needleItem): bool {
        $needleItem = self::prepareString($needleItem);
        $text = self::prepareString($text);
        $ru = LangHelper::switchRu($text);
        $en = LangHelper::switchEn($text);
        $isFound =
            strpos($text, $needleItem) !== false ||
            strpos($ru, $needleItem) !== false ||
            strpos($en, $needleItem) !== false;
        return $isFound;
    }

    private static function stringToArray(string $text): array
    {
        $text = self::prepareString($text);
        $array = TextHelper::getWordArray($text);
        return $array;
    }
}
