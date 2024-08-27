<?php

namespace Untek\Component\Text\Helpers;

class TemplateHelper
{

    public static function render(string $mask, array $data = [], string $beginBlock = '{', string $endBlock = '}')
    {
        $newParams = [];
        foreach ($data as $name => $value) {
            $name = $beginBlock . $name . $endBlock;
            $newParams[$name] = $value;
        }
        return strtr($mask, $newParams);
    }

    public static function getVariableFromTemplate(string $content, string $beginBlock = '{', string $endBlock = '}'): array
    {
        preg_match_all('/' . $beginBlock . '([a-z-_.]+)' . $endBlock . '/i', $content, $matches);
        return $matches[1];
    }

    public static function loadTemplate(string $fileName, array $params = []): string
    {
        ob_start();
        extract($params);
        include($fileName);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}