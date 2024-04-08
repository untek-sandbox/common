<?php


namespace Untek\Framework\Telegram\Domain\Repositories\File;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Lib\Components\Store\StoreFile;

class ConfigRepository
{

    private $longpullTimeout = 30;
    private $token = 30;
    
    public function __construct(?string $token)
    {
        $this->token = $token;
    }

    public function getBotToken() {
        return $this->token;
    }

    public function getLongpullTimeout() {
        return $this->longpullTimeout;
        
//        return $this->getBotConfig('timeout', 30);
    }
    
    /*private function getBotConfig(string $name, $default = null) {
        $mainConfig = include __DIR__ . '/../../../../../../../config/main.php';
        $botConfig = $mainConfig['telegram']['bot'];
        return ArrayHelper::getValue($botConfig, $name, $default);
    }*/
}
