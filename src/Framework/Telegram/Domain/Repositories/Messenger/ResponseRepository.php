<?php


namespace Untek\Framework\Telegram\Domain\Repositories\Messenger;

use Untek\Framework\Telegram\Domain\Entities\BotEntity;
use Untek\Framework\Telegram\Domain\Entities\ResponseEntity;
use Untek\Framework\Telegram\Domain\Helpers\HttpHelper;
use Untek\Framework\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use danog\MadelineProto\Exception;
use Untek\Model\Entity\Helpers\EntityHelper;

//use Untek\Domain\Entity\Helpers\EntityHelper;

class ResponseRepository implements ResponseRepositoryInterface
{

    const URL = 'http://symfony.tpl/api/v1';

    public function send(ResponseEntity $responseEntity, BotEntity $botEntity)
    {
        $data = EntityHelper::toArrayForTablize($responseEntity);

        $query = http_build_query($data);
        $token = $botEntity->getToken();
        $uri = "bot/$token/send-message";
        $url = self::URL . "/$uri?$query";
        $json = HttpHelper::getHtml($url);
        $data = json_decode($json);
        if (empty($data->ok)) {
            //dd($data);
            throw new Exception('Driver error! ' . $json);
        }
    }

}