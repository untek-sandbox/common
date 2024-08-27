<?php

namespace Untek\Core\App\Libs;

use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Yiisoft\Arrays\ArrayHelper;

class ConfigFinder
{

    private array $excludePathes = [];

    public function __construct(
        private string $pathTemplate,
        private ?string $nameTemplate = null,
    )
    {
    }

    public function addExcludePath(string $path): void
    {
        $this->excludePathes[] = $path;
    }

    public function load(array|string $paths, string $rootDirectory, FileLoader $loader): void
    {
        $paths = ArrayHelper::toArray($paths);
        $list = $this->find($paths, $rootDirectory);
        foreach ($list as $item) {
            $loader->load($rootDirectory . '/' . $item);
        }
    }

    public function find(array $paths, string $rootDirectory): array
    {
        $list = [];
        foreach ($paths as $path) {
            $files = (new Finder())
                ->files()
                ->path($this->pathTemplate)
                ->in($path);

            if ($this->nameTemplate) {
                $files->name($this->nameTemplate);
            }

            foreach ($files as $file) {
                /** @var SplFileInfo $file */
                $item = Path::makeRelative($file->getRealPath(), $rootDirectory);
                $isAdd = true;
                if ($this->excludePathes) {
                    foreach ($this->excludePathes as $excludePath) {
                        if (str_contains($item, $excludePath)) {
                            $isAdd = false;
                        }
                    }
                }
                if ($isAdd) {
                    $list[] = $item;
                }
            }
        }
        return $list;
    }
}
