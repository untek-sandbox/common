<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Libs\App;

use Symfony\Component\Process\Process;
use Untek\Core\Pattern\Singleton\SingletonTrait;
use Untek\Framework\Console\Domain\Helpers\CommandLineHelper;

class EnvProcessor
{

    use SingletonTrait;

    public static function locateBinaryPath($name)
    {
        $nameEscaped = escapeshellarg($name);

        // Try `command`, should cover all Bourne-like shells
        // Try `which`, should cover most other cases
        // Fallback to `type` command, if the rest fails

        $process = Process::fromShellCommandline("command -v $nameEscaped || which $nameEscaped || type -p $nameEscaped");
        CommandLineHelper::run($process);
        $path = $process->getOutput();

        if ($path) {
            // Deal with issue when `type -p` outputs something like `type -ap` in some implementations
            return trim(str_replace("$name is", "", $path));
        }

        throw new \RuntimeException("Can't locate [$nameEscaped] - neither of [command|which|type] commands are available");
    }
}
