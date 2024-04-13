<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Libs\App;

use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\Instance\Helpers\InstanceHelper;
use Untek\Core\Pattern\Singleton\SingletonTrait;
use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Lib\Components\ShellRobot\Domain\Factories\ShellFactory;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\TaskInterface;
use Untek\Framework\Console\Domain\Base\BaseShellNew;
use Untek\Framework\Console\Domain\Libs\IO;

class TaskProcessor
{

    use SingletonTrait;

    public static function runTaskList(array $tasks, IO $io): void
    {
        foreach ($tasks as $taskDefinition) {
            $taskInstance = self::createTask($taskDefinition, $io);
            $title = $taskInstance->getTitle();
            if ($title) {
                $title = TemplateHelper::render($title, $taskDefinition, '{{', '}}');
                $title = ShellFactory::getVarProcessor()->process($title);
                $io->writeln($title);
            }
            $taskInstance->run();
        }
    }

    private static function createTask($definition, IO $io): TaskInterface
    {
        $remoteShell = ShellFactory::createRemoteShell();
        $constructParams = [
            BaseShellNew::class => $remoteShell,
            IO::class => $io,
        ];
        $container = ContainerHelper::getContainer();
        return InstanceHelper::create($definition, $constructParams, $container);
    }
}
