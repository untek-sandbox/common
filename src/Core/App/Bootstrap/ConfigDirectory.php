<?php

namespace Untek\Core\App\Bootstrap;

class ConfigDirectory
{

    public function __construct(
        private string $projectDirectory,
    )
    {
    }

    public function getProjectDirectory(): string
    {
        return $this->projectDirectory;
    }

    public function getConfigFile(string $configFileName, string $context = null): string
    {
        return $this->getConfigDirectory($context) . '/' . $configFileName;
    }

    public function getConfigDirectory(string $context = null): string
    {
        if($context) {
            return $this->projectDirectory . '/config/' . $context;
        } else {
            return $this->projectDirectory . '/config/shared';
        }
    }
}
