<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Libs\Shell;

use Untek\Lib\Components\ShellRobot\Domain\Entities\HostEntity;
use Untek\Framework\Console\Domain\Helpers\CommandLineHelper;

class RemoteShell extends LocalShell
{

    private $hostEntity;

    public function getHostEntity(): HostEntity
    {
        return $this->hostEntity;
    }

    public function __construct(HostEntity $hostEntity)
    {
        $this->hostEntity = $hostEntity;
    }

    public function wrapCommand($command): string
    {
        $command = CommandLineHelper::argsToString($command, $this->lang);
        $command = escapeshellarg($command);
        /** @var HostEntity $hostEntity */
        $hostEntity = $this->getHostEntity();
        $port = $hostEntity->getPort();
        $host = "{$hostEntity->getUser()}@{$hostEntity->getHost()}";
        $cmd = "ssh -p $port $host $command";
        return $cmd;
    }

    public function runCommand($command, ?string $path = null): string
    {
        $ssh = $this->wrapCommand($command);
        $commandOutput = parent::runCommand($ssh);
        return $commandOutput;
    }
}
