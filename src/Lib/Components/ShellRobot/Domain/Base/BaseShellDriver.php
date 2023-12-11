<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Base;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Lib\Components\ShellRobot\Domain\Factories\ShellFactory;
use Untek\Framework\Console\Domain\Base\BaseShellNew;

abstract class BaseShellDriver
{

    protected $shell;
    private $sudo = false;
    private $directory;

    public function __construct(BaseShellNew $shell)
    {
        $this->shell = $shell;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory)
    {
        $this->directory = $directory;
    }

    /*public function isSudo(): bool
    {
        return $this->shell->isSudo();
    }

    public function sudo($sudo = true) {
        $this->sudo = $sudo;
    }*/

    public function setSudo(bool $sudo): void
    {
        $this->shell->setSudo($sudo);
    }

    public function sudo($sudo = true): self
    {
        $this->sudo = $sudo;
        $clone = clone $this;
        $clone->setSudo($sudo);
        return $clone;
    }

    protected function prepareSudo(string $command): string
    {
        $sudoCmdTpl = static::getSudoCommandTemplate();
        $commands = explode('&&', $command);
        foreach ($commands as &$commandItem) {
            $commandItem = trim($commandItem);
            if ($this->isSudo($commandItem) || $this->sudo) {
                $commandItem = $this->stripSudo($commandItem);
                $commandItem = str_replace('{command}', $commandItem, $sudoCmdTpl);
            }
        }
        $command = implode(' && ', $commands);
        return $command;
    }

    public function runCommand($command, ?string $path = null): string
    {
        $command = $this->prepareSudo($command);
        if ($this->getDirectory()) {
            $dir = ($this->getDirectory());
            $command = "cd {$dir} && $command";
        }
        return $this->shell->runCommand($command, $path);
    }

    protected static function getSudoCommandTemplate()
    {
        $connection = ShellFactory::getConnectionProcessor()->getCurrent();
        return ArrayHelper::getValue($connection, 'sudo.commandTemplate', 'sudo {command}');
    }

    protected static function getSudoCommandName(): string
    {
        $connection = ShellFactory::getConnectionProcessor()->getCurrent();
        return ArrayHelper::getValue($connection, 'sudo.command', 'sudo');
    }

    protected function stripSudo(string $command): string
    {
        $command = trim($command);
        $command = preg_replace('/^(' . static::getSudoCommandName() . '\s+)/i', '', $command);
        return $command;
    }

    protected function isSudo(string $command): bool
    {
        $command = trim($command);
        return strpos($command, static::getSudoCommandName()) === 0;
    }

    /*public function test($command, ?string $path = null): string
    {
        return $this->shell->test($command, $path);
    }*/
}
