<?php

namespace Untek\Framework\Telegram\Domain\Actions;

use danog\MadelineProto\APIFactory;
use danog\MadelineProto\EventHandler;
use Untek\Framework\Telegram\Domain\Base\BaseAction;
use Untek\Framework\Telegram\Domain\Entities\MessageEntity;
use Untek\Framework\Telegram\Domain\Entities\RequestEntity;

class ShutdownHandlerAction extends BaseAction
{

    private $eventHandler;

    public function __construct(EventHandler $eventHandler)
    {
        parent::__construct($messages);
        $this->eventHandler = $eventHandler;
    }

    public function run(RequestEntity $messageEntity)
    {
        $this->eventHandler->stop();
    }

}