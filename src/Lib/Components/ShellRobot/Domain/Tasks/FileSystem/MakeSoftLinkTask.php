<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Tasks\FileSystem;

use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShell;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\TaskInterface;
use Untek\Lib\Components\ShellRobot\Domain\Repositories\Shell\FileSystemShell;

class MakeSoftLinkTask extends BaseShell implements TaskInterface
{

    public $sourceFilePath = null;
    public $linkFilePath = null;
    protected $title = 'Make link "{{linkFilePath}}" > "{{sourceFilePath}}"';

    public function run()
    {
        $fs = new FileSystemShell($this->remoteShell);
        $fs->sudo()->removeAny($this->linkFilePath);
        $fs->sudo()->makeLink($this->sourceFilePath, $this->linkFilePath, '-s');
    }
}
