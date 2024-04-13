<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Alert;

use Untek\Component\Web\Widget\Base\BaseWidget2;

class AlertWidget extends BaseWidget2
{

    static private $collection = [];

    public static function add(string $message, string $type = null): void
    {
        self::$collection[] = [
            'message' => $message,
            'type' => $type,
        ];
    }

    public function run(): string
    {
        $html = '';
        foreach (self::$collection as $item) {
            $type = $item['type'] ?: 'primary';
            $html .= self::generateItemCode($item['message'], $type);
        }
        return $html;
    }

    private function generateItemCode(string $message, string $type = null) {
        return '<div class="alert alert-' . $type . '" role="alert">
  ' . $message . '
</div>';
    }
}
