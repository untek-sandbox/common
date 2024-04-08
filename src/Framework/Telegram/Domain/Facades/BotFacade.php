<?php

namespace Untek\Framework\Telegram\Domain\Facades;

use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\Container\Libs\Container;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Framework\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use Untek\Framework\Telegram\Domain\Repositories\Telegram\ResponseRepository;
use Untek\Framework\Telegram\Domain\Services\BotService;
use Untek\Framework\Telegram\Domain\Services\RequestService;
use Untek\Framework\Telegram\Domain\Services\ResponseService;

class BotFacade
{

    public static function getResponseService(string $token): ResponseService
    {
        /** @var Container $container */
        $container = ContainerHelper::getContainer();

        /** @var ContainerConfiguratorInterface $containerConfigurator */
        $containerConfigurator = $container->get(ContainerConfiguratorInterface::class);
//        $containerConfigurator = ContainerHelper::getContainerConfiguratorByContainer($container);
        $containerConfigurator->singleton(ResponseRepositoryInterface::class, ResponseRepository::class);
        $containerConfigurator->singleton(BotService::class, BotService::class);

//        $container->singleton(ResponseRepositoryInterface::class, ResponseRepository::class);
//        $container->singleton(BotService::class, BotService::class);
        $botService = $container->get(BotService::class);
        $botService->authByToken($token);
        /** @var RequestService $requestService */
//        $requestService = $container->get(RequestService::class);
        /** @var ResponseService $responseService */
        $responseService = $container->get(ResponseService::class);
        return $responseService;
    }
}
