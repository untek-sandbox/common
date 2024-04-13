<?php

namespace Untek\Framework\Telegram\Domain\Actions;

use Untek\Framework\Telegram\Domain\Base\BaseAction;
use Untek\Framework\Telegram\Domain\Entities\RequestEntity;

class EchoAction extends BaseAction
{

    public function run(RequestEntity $requestEntity)
    {
        $this->response->sendMessage($requestEntity->getMessage()->getChat()->getId(), $requestEntity->getMessage()->getText());
    }

}