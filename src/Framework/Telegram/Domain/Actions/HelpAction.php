<?php

namespace Untek\Framework\Telegram\Domain\Actions;

use danog\MadelineProto\APIFactory;
use Untek\Framework\Telegram\Domain\Base\BaseAction;
use Untek\Framework\Telegram\Domain\Entities\MessageEntity;
use Untek\Framework\Telegram\Domain\Entities\RequestEntity;
use Untek\Framework\Telegram\Domain\Handlers\BaseInputMessageEventHandler;
use Untek\Core\Arr\Helpers\ArrayHelper;

class HelpAction extends BaseAction
{

    /** @var BaseInputMessageEventHandler */
    public $eventHandler;

    public function __construct(/*BaseInputMessageEventHandler*/ $eventHandler)
    {
        parent::__construct();
        $this->eventHandler = $eventHandler;
    }

    public function run(RequestEntity $messageEntity)
    {
        $definitions = $this->eventHandler->definitions($this->response->getApi());
        $lines = [];
        foreach ($definitions as $definition) {
            if(!empty($definition['help'])) {
                $help = ArrayHelper::toArray($definition['help']);
                $lines[] = implode(PHP_EOL, $help);
            }
        }
        return $this->response->sendMessage(implode(PHP_EOL . PHP_EOL, $lines), $messageEntity->getUserId());
    }
}