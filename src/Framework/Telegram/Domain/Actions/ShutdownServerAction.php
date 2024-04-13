<?php

namespace Untek\Framework\Telegram\Domain\Actions;

use danog\MadelineProto\APIFactory;
use danog\MadelineProto\EventHandler;
use Untek\Core\Env\Enums\OsFamilyEnum;
use Untek\Core\Env\Helpers\OsHelper;
use Untek\Framework\Telegram\Domain\Base\BaseAction;
use Untek\Framework\Telegram\Domain\Entities\MessageEntity;
use Untek\Framework\Telegram\Domain\Entities\RequestEntity;

class ShutdownServerAction extends BaseAction
{

    /*private $eventHandler;

    public function __construct(EventHandler $eventHandler)
    {
        parent::__construct();
        $this->eventHandler = $eventHandler;
    }*/

    public function run(RequestEntity $requestEntity)
    {
        //return $this->response->sendMessage($requestEntity->getMessage()->getChat()->getId(), $this->text);
        // powercfg /hibernate off
        //shell_exec('rundll32.exe powrprof.dll,SetSuspendState 0,1,0');
        if(OsHelper::isFamily(OsFamilyEnum::LINUX)) {
            shell_exec('systemctl suspend');
        } else {
            shell_exec('Rundll32.exe powrprof.dll,SetSuspendState 0');
        }


    }

}