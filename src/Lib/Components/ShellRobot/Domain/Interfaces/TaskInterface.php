<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Interfaces;

interface TaskInterface
{

    public function run();

    public function getTitle(): ?string;
}
