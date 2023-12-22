<?php

namespace Untek\Framework\Socket\Domain\Apps;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\DotEnv\Domain\Libs\DotEnv;
use Untek\Framework\Socket\Domain\Apps\Base\BaseWebSocketApp;

DeprecateHelper::hardThrow();

class WebSocketApp extends BaseWebSocketApp
{

    /*protected function bundles(): array
    {
        $bundles = [
            \Untek\Database\Eloquent\Bundle::class,
        ];
        if (getenv('BUNDLES_CONFIG_FILE')) {
            $bundles = ArrayHelper::merge($bundles, include DotEnv::get('BUNDLES_CONFIG_FILE'));
        }
        return $bundles;
    }*/
}
