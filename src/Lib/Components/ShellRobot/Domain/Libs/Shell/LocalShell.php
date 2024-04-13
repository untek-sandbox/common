<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Libs\Shell;

use Untek\Lib\Components\ShellRobot\Domain\Factories\ShellFactory;
use Untek\Framework\Console\Domain\Base\BaseShellNew;

class LocalShell extends BaseShellNew
{

    protected function prepareCommandString(string $commandString): string
    {
        $commandString = ShellFactory::getVarProcessor()->process($commandString);
        return $commandString;
    }
}
