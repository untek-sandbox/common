<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Base;

use Untek\Lib\Components\ShellRobot\Domain\Libs\Shell\LocalShell;
use Untek\Framework\Console\Domain\Base\BaseShellNew;
use Untek\Framework\Console\Domain\Libs\IO;

abstract class BaseShell
{

    protected $localShell;
    protected $remoteShell;
    protected $io;
    protected $title;

    public function getTitle(): ?string
    {
        if ($this->title == null) {
            return static::class;
        }
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function __construct(BaseShellNew $remoteShell, IO $io)
    {
        $this->localShell = new LocalShell();
        $this->remoteShell = $remoteShell;
        $this->io = $io;
    }
}
