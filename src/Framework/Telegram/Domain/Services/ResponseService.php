<?php


namespace Untek\Framework\Telegram\Domain\Services;

use Untek\Framework\Telegram\Domain\Entities\ResponseEntity;
use Untek\Framework\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use Throwable;

class ResponseService
{

    /** @var BotService */
    private $botService;

    /** @var ResponseRepositoryInterface */
    private $responseRepository;

    public function __construct(BotService $botService, ResponseRepositoryInterface $responseRepository)
    {
        $this->botService = $botService;
        $this->responseRepository = $responseRepository;
    }

    public function sendMessage(int $chatId, string $text)
    {
        $responseEntity = new ResponseEntity;
        $responseEntity->setChatId($chatId);
        $responseEntity->setText($text);
        $responseEntity->setParseMode('HTML');
        $responseEntity->setDisableWebPagePreview('false');
        $responseEntity->setDisableNotification('false');
        $this->send($responseEntity);
    }

    public function showError(string $message, int $statusCode = 500) {
        http_response_code($statusCode);
        echo $message;
    }

    public function send(ResponseEntity $responseEntity) {

        $botEntity = $this->botService->getIdentity();
        try {
            $this->responseRepository->send($responseEntity, $botEntity);
        } catch (Throwable $e) {
            $this->showError($e->getMessage());
        }
    }
}