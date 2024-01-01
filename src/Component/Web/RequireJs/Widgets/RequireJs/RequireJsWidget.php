<?php

namespace Untek\Component\Web\RequireJs\Widgets\RequireJs;

use Untek\Component\Web\Widget\Base\BaseWidget2;

class RequireJsWidget extends BaseWidget2
{

    public $scripts = [];

    public static function require(string $url): string
    {
        return self::widget([
            'scripts' => [
                $url
            ],
        ]);
    }

    public function run(): string
    {
        $jsonList = json_encode($this->scripts);
        return '<script>require(' . $jsonList . ')</script>';
    }
}