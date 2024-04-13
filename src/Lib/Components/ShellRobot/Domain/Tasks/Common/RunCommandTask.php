<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Tasks\Common;

use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShell;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\TaskInterface;

class RunCommandTask extends BaseShell implements TaskInterface
{

    protected $title = 'Run "{{command}}"';
    public $command = null;

    public function run()
    {
        $this->remoteShell->runCommand($this->command);
    }
}
