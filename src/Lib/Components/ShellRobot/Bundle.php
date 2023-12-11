<?php

namespace Untek\Lib\Components\ShellRobot;

use Untek\Core\Bundle\Base\BaseBundle;
use Untek\Framework\Console\Symfony4\Libs\CommandConfigurator;
use Untek\Lib\Components\ShellRobot\Commands\ShellRobotTaskCommand;

class Bundle extends BaseBundle
{

    public function getName(): string
    {
        return 'shellRobot';
    }

    /*public function console(): array
    {
        return [
            'Untek\Lib\Components\ShellRobot\Commands',
        ];
    }*/

    public function consoleCommands(CommandConfigurator $commandConfigurator)
    {
        $commandConfigurator->registerCommandClass(ShellRobotTaskCommand::class);
    }

    public function container(): array
    {
        return [
            __DIR__ . '/Domain/config/container.php',
            __DIR__ . '/Domain/config/container-script.php',
        ];
    }
}
