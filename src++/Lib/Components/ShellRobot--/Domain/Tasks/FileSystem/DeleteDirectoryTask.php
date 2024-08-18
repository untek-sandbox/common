<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Tasks\FileSystem;

use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShell;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\TaskInterface;
use Untek\Lib\Components\ShellRobot\Domain\Repositories\Shell\FileSystemShell;

class DeleteDirectoryTask extends BaseShell implements TaskInterface
{

    public $directoryPath = null;
    protected $title = 'Delete directory "{{directoryPath}}"';

    public function run()
    {
        $fs = new FileSystemShell($this->remoteShell);
        $fs->sudo()->removeDir($this->directoryPath);
    }
}
