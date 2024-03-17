<?php

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\PersistingStoreInterface;
use Symfony\Component\Lock\Store\SemaphoreStore;
use Untek\Core\Env\Helpers\EnvHelper;
use Untek\Core\Instance\Libs\InstanceProvider;
use Untek\Core\Instance\Libs\Resolvers\InstanceResolver;
use Untek\Framework\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use Untek\Framework\Telegram\Domain\Repositories\File\ConfigRepository;
use Untek\Framework\Telegram\Domain\Repositories\File\StoreRepository;
use Untek\Framework\Telegram\Domain\Repositories\Http\RequestRepository;
use Untek\Framework\Telegram\Domain\Repositories\Http\UpdatesRepository;
use Untek\Framework\Telegram\Domain\Repositories\Telegram\ResponseRepository as TelegramResponseRepository;
use Untek\Framework\Telegram\Domain\Repositories\Test\ResponseRepository as TestResponseRepository;
use Untek\Framework\Telegram\Domain\Services\BotService;
use Untek\Framework\Telegram\Domain\Services\LongPullService;
use Untek\Framework\Telegram\Domain\Services\RequestService;
use Untek\Framework\Telegram\Domain\Services\ResponseService;
use Untek\Framework\Telegram\Domain\Services\RouteService;
use Untek\Framework\Telegram\Symfony4\Commands\LongPullCommand;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(StoreRepository::class, StoreRepository::class)
    ->args([
        getenv('VAR_DIRECTORY') . '/telegram/server/state.json',
    ]);

    $services->set(UpdatesRepository::class, UpdatesRepository::class);
    $services->set(ConfigRepository::class, ConfigRepository::class)
        ->args([
            getenv('TELEGRAM_BOT_TOKEN'),
        ]);

    $services->set(RequestRepository::class, RequestRepository::class);
    $services->set(RequestService::class, RequestService::class)
        ->args([
            service(RequestRepository::class)
        ]);

    if (EnvHelper::isTest()) {
        $services->set(ResponseRepositoryInterface::class, TestResponseRepository::class)
            ->args([
                service(RequestService::class),
                getenv('VAR_DIRECTORY') . '/telegram/response',
            ]);
    } else {
        $services->set(ResponseRepositoryInterface::class, TelegramResponseRepository::class);
    }

    $services->set(ResponseService::class, ResponseService::class)
        ->args([
            service(BotService::class),
            service(ResponseRepositoryInterface::class),
        ]);
    $services->set(BotService::class, BotService::class);

    $services->set(LongPullService::class, LongPullService::class)
        ->args(
            [
                service(StoreRepository::class),
                service(UpdatesRepository::class),
                service(ConfigRepository::class),
                service(LoggerInterface::class),
                service(RequestService::class),
                service(ResponseService::class),
                service(BotService::class),
                service(RouteService::class),
            ]
        );

    $services->set(PersistingStoreInterface::class, SemaphoreStore::class);

    $services->set(LockFactory::class, LockFactory::class)
        ->args(
            [
                service(PersistingStoreInterface::class),
            ]
        );

    $services->set(LongPullCommand::class, LongPullCommand::class)
        ->args(
            [
                service(LongPullService::class),
                service(ConfigRepository::class),
                service(LockFactory::class),
                service(ContainerInterface::class),
            ]
        );
};