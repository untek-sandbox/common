<?php

namespace Untek\Component\Text\Helpers;

class TextHelper
{

    const PATTERN_SPACES = '#\s+#m';
    const WITHOUT_CHAR = '#\s+#m';
    const NUM_CHAR = '#\D+#m';

    /**
     * Нормализовать переносы строк
     *
     * @param string $value
     * @return string
     */
    public static function normalizeLineBreaks(string $value): string
    {
        return preg_replace('/[\r\n]+/', PHP_EOL, $value);
    }

    public static function fill($value, $length, $char, $place = 'after')
    {
        $value = strval($value);
        $len = mb_strlen($value);
        if ($length > $len) {
            $mock = str_repeat($char, $length - $len);
            if ($place == 'after') {
                $value = $value . $mock;
            } else {
                $value = $mock . $value;
            }
        }
        return $value;
    }

    public static function setTab($content, $tabCount)
    {
        $content = str_replace(str_repeat(' ', $tabCount), "\t", $content);
        return $content;
    }

    public static function getWordArray($content)
    {
        $content = self::extractWords($content);
        return self::textToArray($content);
    }

    public static function normalizeNewLines($text)
    {
        $text = str_replace(PHP_EOL, "\n", $text);
        return $text;
    }

    public static function textToLines($text)
    {
        $text = self::normalizeNewLines($text);
        $text = explode("\n", $text);
        return $text;
    }

    public static function removeDoubleSpace($text)
    {
        return preg_replace(self::PATTERN_SPACES, ' ', $text);
    }

    public static function removeDoubleChar($text, string $char)
    {
        $text = preg_replace('#' . preg_quote($char) . '+#m', $char, $text);
        return $text;
    }

    public static function removeAllSpace($text)
    {
        $text = preg_replace(self::PATTERN_SPACES, '', $text);
        return $text;
    }

    public static function filterNumOnly($text, $charSet = self::NUM_CHAR)
    {
        $text = preg_replace($charSet, '', $text);
        return $text;
    }

    public static function filterChar($text, $charSet = self::WITHOUT_CHAR)
    {
        $text = preg_replace($charSet, '', $text);
        return $text;
    }

    public static function textToArray($text)
    {
        $text = self::removeDoubleSpace($text);
        return explode(' ', $text);
    }

    public static function mask($value, $length = 2, $valueLength = null)
    {
        if (empty($value)) {
            return '';
        }
        if ($length == 0) {
            $begin = '';
            $end = '';
        } else {
            $begin = substr($value, 0, $length);
            $end = substr($value, 0 - $length);
        }
        $valueLength = !empty($valueLength) ? $valueLength : strlen($value) - $length * 2;
        $valueLength = $valueLength > 1 ? $valueLength : 2;
        return $begin . str_repeat('*', $valueLength) . $end;
    }

    public static function extractWords($text)
    {
        $text = preg_replace('/[^0-9A-Za-zА-Яа-яЁё]/iu', ' ', $text);
        $text = self::removeDoubleSpace($text);
        $text = trim($text);
        return $text;
    }
}