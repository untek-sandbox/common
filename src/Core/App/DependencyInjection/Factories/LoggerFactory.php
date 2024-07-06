<?php

namespace Untek\Core\App\DependencyInjection\Factories;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Utility\Logger\Infrastructure\Handlers\EloquentHandler;

class LoggerFactory
{

    public static function createJsonFileLogger(string $context): LoggerInterface
    {
        $directory = getenv('LOG_DIRECTORY');
        $time = date('Y-m-d');
        $logFileName = "{$directory}/{$time}.log";

        $level = self::getLevel();
        $handler = new StreamHandler($logFileName, $level);
        $formatter = new JsonFormatter();
        $formatter->includeStacktraces();
        if (!getenv('APP_ENV') !== 'prod') {
            $formatter->setJsonPrettyPrint(true);
        }
        $handler->setFormatter($formatter);

        $logger = new Logger($context);
        $logger->pushHandler($handler);
        return $logger;
    }

    public static function createDatabaseLogger(string $context): LoggerInterface
    {
        $manager = ContainerHelper::getContainer()->get(Manager::class);
        $level = self::getLevel();
        $handler = new EloquentHandler($manager, $level);

        $logger = new Logger($context);
        $logger->pushHandler($handler);
        return $logger;
    }

    private static function getLevel(): Level
    {
        if (getenv('APP_ENV') !== 'prod') {
            $level = Level::Debug;
        } else {
            $level = Level::Error;
        }
        return $level;
    }
}