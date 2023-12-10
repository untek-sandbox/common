<?php

namespace Untek\Framework\Telegram\Domain\Actions;

use danog\MadelineProto\APIFactory;
use Untek\Framework\Telegram\Domain\Base\BaseAction;
use Untek\Framework\Telegram\Domain\Entities\MessageEntity;
use Untek\Framework\Telegram\Domain\Entities\RequestEntity;
use Untek\Framework\Telegram\Domain\Entities\ResponseEntity;

class SaveDraftAction extends BaseAction
{

    private $text;

    public function __construct(string $text)
    {
        parent::__construct();
        $this->text = $text;
    }

    public function run(RequestEntity $messageEntity)
    {
        $responseEntity = new ResponseEntity;
        $responseEntity->setUserId($messageEntity->getUserId());
        $responseEntity->setMessage($this->text);
        $responseEntity->setMethod('saveDraft');
        return $this->response->send($responseEntity);
        /*return $this->messages->saveDraft([
            'peer' => $messageEntity->getUserId(),
            'message' => $this->text,
        ]);*/
    }
}
