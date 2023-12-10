<?php


namespace Untek\Framework\Telegram\Domain\Repositories\Test;

use Untek\Framework\Telegram\Domain\Entities\BotEntity;
use Untek\Framework\Telegram\Domain\Entities\ResponseEntity;
use Untek\Framework\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use Untek\Framework\Telegram\Domain\Services\RequestService;
use Untek\Lib\Components\Store\StoreFile;
use Untek\Model\Entity\Helpers\EntityHelper;

class ResponseRepository implements ResponseRepositoryInterface
{

    private $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function send(ResponseEntity $responseEntity, BotEntity $botEntity)
    {
        $data = EntityHelper::toArrayForTablize($responseEntity);
        $file = $this->fileName($botEntity->getId(), $responseEntity->getChatId());
        $store = new StoreFile($file);
        $collection = $store->load();

        $requestData = [
            "chat_id" => $botEntity->getId(),
            "text" => $this->requestService->getRequest()->getMessage()->getText(),
            /*"parse_mode" => "HTML",
            "disable_web_page_preview" => "false",
            "disable_notification" => "false"*/
        ];

        $collection[] = $requestData;
        $collection[] = $data;
        $store->save($collection);
    }

    public function findAll(int $botId, int $chatId)
    {
        $file = $this->fileName($botId, $chatId);
        $store = new StoreFile($file);
        return $store->load();
    }

    private function fileName(int $botId, int $chatId)
    {
        return __DIR__ . '/../../../../var/dev/chat/' . $botId . '.json';
    }
}