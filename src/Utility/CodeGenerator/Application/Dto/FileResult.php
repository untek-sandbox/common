<?php

namespace Untek\Utility\CodeGenerator\Application\Dto;

use Untek\Core\FileSystem\Helpers\FileHelper;
use Untek\Utility\CodeGenerator\Application\Interfaces\ResultInterface;

class FileResult implements ResultInterface
{

    private ?string $name = null;
    private string $content;
    private bool $isNew = false;

    public function __construct(
        string $name,
        string $content,
        ?bool $isNew = null,
    )
    {
        $name = FileHelper::normalizePath($name);
        $this->name = $name;
        $this->content = $content;
        $this->isNew = $isNew === null ? !file_exists($name) : $isNew;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isNew(): bool
    {
        return $this->isNew;
    }

    /*private function normalizeFileName(string $fileName): string
    {
        $fileName1 = realpath($fileName);
        if (!empty($fileName1)) {
            $fileName = $fileName1;
        }
        return $fileName;
    }*/
}