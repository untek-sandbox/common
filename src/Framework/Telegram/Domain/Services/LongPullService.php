<?php

namespace Untek\Framework\Telegram\Domain\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;
use Untek\Core\Contract\Common\Exceptions\InternalServerErrorException;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Framework\Telegram\Domain\Helpers\RequestHelper;
use Untek\Framework\Telegram\Domain\Repositories\File\ConfigRepository;
use Untek\Framework\Telegram\Domain\Repositories\File\StoreRepository;
use Untek\Framework\Telegram\Domain\Repositories\Http\UpdatesRepository;

class LongPullService
{

    protected $storeRepository;
    protected $updatesRepository;
    protected $configRepository;
    protected $logger;
    
    /** @var RequestService */
    private $requestService;

    /** @var ResponseService */
    private $responseService;

    /** @var BotService */
    private $botService;

    /** @var RouteService */
    private $routeService;

    public function __construct(
        StoreRepository $storeRepository, 
        UpdatesRepository $updatesRepository,
        ConfigRepository $configRepository,
        LoggerInterface $logger,

        RequestService $requestService,
        ResponseService $responseService,
        BotService $botService,
        RouteService $routeService
    )
    {
        $this->storeRepository = $storeRepository;
        $this->updatesRepository = $updatesRepository;
        $this->configRepository = $configRepository;
        $this->logger = $logger;

        $this->requestService = $requestService;
        $this->responseService = $responseService;
        $this->botService = $botService;
        $this->routeService = $routeService;

        $token = $this->configRepository->getBotToken();
        if($token) {
            $this->botService->authByToken($token);
        }
    }

    public function findAll() {
        $lastId = $this->storeRepository->getLastId();
        $token = $this->configRepository->getBotToken();
        $timeout = $this->configRepository->getLongpullTimeout();
        $updates = $this->updatesRepository->findAll($token, $lastId + 1, $timeout);
        return $updates;
    }
    
    public function setHandled(array $update) {
        $this->storeRepository->setLastId($update['update_id']);
    }

    public function handleUpdates(array $updates) {
        foreach ($updates as $update) {
            $this->runBot($update);
        }
    }

    public function runBotFromService(array $update)
    {
        $requestEntity = RequestHelper::forgeRequestEntityFromUpdateArray($update);
        if ($requestEntity->getMessage()) {
            if ($requestEntity->getMessage()->getChat()->getType() == "private") {
                $this->routeService->onUpdateNewMessage($requestEntity);
            }
        } elseif ($requestEntity->getCallbackQuery()) {
            $this->routeService->onUpdateNewMessage($requestEntity);
        }
        $this->setHandled($update);
    }
    
    public function runBotFromHttp(array $update)
    {
        $token = $this->configRepository->getBotToken();
        $botUrl = "http://telegram-client.tpl/bot.php?token={$token}";
        $client = new Client();
        try {
            $response = $client->post($botUrl, [
                RequestOptions::JSON => $update
            ]);
            $this->setHandled($update);
        } catch (ServerException $e) {
            $this->setHandled($update);
            $response = $e->getResponse();
            $message = $response->getBody()->getContents();
            $this->logger->error($message, ['request' => $update]);
            throw new InternalServerErrorException($message);
        }
    }
}
