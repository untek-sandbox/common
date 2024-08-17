<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Tasks\LinuxPackage;

use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShell;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\TaskInterface;
use Untek\Lib\Components\ShellRobot\Domain\Repositories\Shell\PackageShell;

class AddPackageRepositoryTask extends BaseShell implements TaskInterface
{

    public $repository = null;
    protected $title = 'Add package repository "{{repository}}"';

    public function run()
    {
        $packageShell = new PackageShell($this->remoteShell);
        $packageShell->addRepository($this->repository);
    }
}
