<?php


namespace Untek\Framework\Telegram\Domain\Services;

use Untek\Framework\Telegram\Domain\Entities\BotEntity;
use Exception;

class BotService
{

    private $identity;

    public function getIdentity(): BotEntity
    {

        if($this->identity) {
            return $this->identity;
        } else {
            throw new Exception('Unathorized!!');
        }
    }

    public function authByToken(string $token) {
        if(preg_match('/(\d+):(.+)/i', $token, $matches)) {
            list($all, $botId, $authKey) = $matches;
            $botEntity = new BotEntity;
            $botEntity->setId($botId);
            $botEntity->setKey($authKey);
            $this->identity = $botEntity;
        } else {
            throw new Exception('Fail token!');
        }
    }
}