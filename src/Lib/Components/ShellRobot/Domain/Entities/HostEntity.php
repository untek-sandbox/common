<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Entities;

use GuzzleHttp\Psr7\Uri;

class HostEntity
{

    private $host = null;
    private $port = 22;
    private $user = null;

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function setPort(string $port): void
    {
        $this->port = $port;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    public function getDsn(): string
    {
        $uri = new Uri();
        $uri = $uri
            ->withScheme('ssh')
            ->withHost($this->getHost())
            ->withPort($this->getPort())
            ->withUserInfo($this->getUser());
        return $uri->__toString();
    }

    /*public function getSshOption(): string
    {

    }*/
}
