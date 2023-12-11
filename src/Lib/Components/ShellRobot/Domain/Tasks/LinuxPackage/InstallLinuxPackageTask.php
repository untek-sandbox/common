<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Tasks\LinuxPackage;

use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShell;
use Untek\Lib\Components\ShellRobot\Domain\Factories\ShellFactory;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\TaskInterface;
use Untek\Lib\Components\ShellRobot\Domain\Repositories\Shell\PackageShell;

class InstallLinuxPackageTask extends BaseShell implements TaskInterface
{

    public $package = null;
    public $withUpdate = false;

//    protected $title = 'Install packags "{{package}}"';

    public function getTitle(): ?string
    {
        if (is_array($this->package)) {
            $package = implode(', ', ShellFactory::getVarProcessor()->processList($this->package));
        } else {
            $package = ShellFactory::getVarProcessor()->process($this->package);
        }
        return "Install packages \"{$package}\"";
    }

    public function run()
    {
        $packageShell = new PackageShell($this->remoteShell);
        if ($this->withUpdate) {
            $packageShell->update();
        }
        if (is_array($this->package)) {
            $packageShell->installBatch(ShellFactory::getVarProcessor()->processList($this->package));
        } else {
            $packageShell->install(ShellFactory::getVarProcessor()->process($this->package));
        }
    }
}
