<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Tasks\Common;

use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShell;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\TaskInterface;
use Untek\Lib\Components\ShellRobot\Domain\Libs\App\TaskProcessor;

class ChainTask extends BaseShell implements TaskInterface
{

    public $tasks = [];

    public function run()
    {
        TaskProcessor::runTaskList($this->tasks);
    }
}
