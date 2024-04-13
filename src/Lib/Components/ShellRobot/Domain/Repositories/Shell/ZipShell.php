<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Repositories\Shell;

use Untek\Lib\Components\ShellRobot\Domain\Base\BaseShellDriver;

class ZipShell extends BaseShellDriver
{

    private $directory;

    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }

    public function unZipAllToDir(string $zipFile, string $targetDirectory)
    {
        $this->shell->runCommand("cd \"{$targetDirectory}\" && unzip -o \"{$zipFile}\"");
    }
}
