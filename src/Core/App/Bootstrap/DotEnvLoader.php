<?php

namespace Untek\Core\App\Bootstrap;

use Untek\Core\DotEnv\Domain\Libs\Vlucas\VlucasBootstrap;

class DotEnvLoader
{

    private string $projectDirectory;
    private string $mode;
    private string $context;

    public function __construct(string $projectProject, string $context, bool $isTest = false)
    {
        $this->projectDirectory = $projectProject;
        $this->mode = $isTest ? 'test' : 'main';
        $this->context = $context;
    }

    public function load(string $path = null)
    {
        $names = $this->getFileNames($this->mode);
        $environmentBootstrap = new VlucasBootstrap($this->mode, $this->projectDirectory);
        $environmentBootstrap->loadFromArray(
            [
                'APP_CONTEXT' => $this->context,
                'APP_MODE' => $this->mode,
            ]
        );
        $environmentBootstrap->loadFromPath($path ?: $this->projectDirectory, $names);
    }

    private function getFileNames(string $mode): array
    {
        return [
            '.env',
            $mode == 'test' ? '.env.test' : '.env.local',
        ];
    }
}
