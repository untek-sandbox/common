<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Tasks\FileSystem;

use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShell;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\TaskInterface;
use Untek\Lib\Components\ShellRobot\Domain\Repositories\Shell\FileSystemShell;

class SetPermissionTask extends BaseShell implements TaskInterface
{

    public $config;
    protected $title = 'Set permissions';

    public function run()
    {
        $fs = new FileSystemShell($this->remoteShell);
        if (isset($this->config)) {
            foreach ($this->config as $item) {
                if (isset($item['permission'])) {
                    $this->io->writeln("  chmod '{$item['permission']}' to '{$item['path']}' ... ");
                    $fs->sudo()->chmod($item['path'], $item['permission'], true);
                }
                if (isset($item['owner'])) {
                    $this->io->writeln("  chown '{$item['owner']}' to '{$item['path']}' ... ");
                    $fs->sudo()->chown($item['path'], $item['owner'], true);
                }
            }
        }
    }
}
