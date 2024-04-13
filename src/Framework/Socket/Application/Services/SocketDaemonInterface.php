<?php

namespace Untek\Framework\Socket\Application\Services;

interface SocketDaemonInterface
{

    public function runAll(bool $daemonize = false);
}
