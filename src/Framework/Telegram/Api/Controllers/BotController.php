<?php

namespace Untek\Framework\Telegram\Api\Controllers;

use Untek\Framework\Telegram\Domain\Handlers\DialogEventHandler2;
use Untek\Framework\Telegram\Domain\Services\BotService;
use Untek\Framework\Telegram\Domain\Services\RequestService;
use Untek\Framework\Telegram\Domain\Services\ResponseService;
use Untek\Framework\Telegram\Domain\Services\RouteService;

class BotController
{
    /** @var RequestService */
    private $requestService;

    /** @var ResponseService */
    private $responseService;

    /** @var BotService */
    private $botService;

    /** @var RouteService */
    private $routeService;

    public function __construct(
        RequestService $requestService, 
        ResponseService $responseService, 
        BotService $botService, 
        RouteService $routeService
    )
    {
        $this->requestService = $requestService;
        $this->responseService = $responseService;
        $this->botService = $botService;
        $this->routeService = $routeService;
        $this->beforeAction();
    }

    public function beforeAction()
    {
        $token = $this->requestService->getToken();
        $this->botService->authByToken($token);
    }

    public function index()
    {
        $requestEntity = $this->requestService->getRequest();
        if ($requestEntity->getMessage()) {
            if ($requestEntity->getMessage()->getChat()->getType() == "private") {
                $this->routeService->onUpdateNewMessage($requestEntity);
            }
        } elseif ($requestEntity->getCallbackQuery()) {
            $this->routeService->onUpdateNewMessage($requestEntity);
        }
    }

    /*private function router(RequestEntity $requestEntity)
    {
        // проверяем на объект Message https://core.telegram.org/bots/api#message
        if (array_key_exists("message", $this->data)) {
            // если это текстовое сообщение объекта Message

            // если это объект СallbackQuery https://core.telegram.org/bots/api#callbackquery
        } elseif (array_key_exists("callback_query", $this->data)) {
            // получаем значение (название метода) под ключем 0 из callback_data кнопки inline
            $method = current(explode("_", $this->data['callback_query']['data']));
            // вызываем переданный метод и передаем в него весь объект callback_query
            $this->$method($this->data['callback_query']);
        } else {
            return false;
        }
    }*/
}
