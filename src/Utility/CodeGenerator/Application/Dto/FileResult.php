<?php

namespace Untek\Utility\CodeGenerator\Application\Dto;

use Untek\Utility\CodeGenerator\Application\Interfaces\ResultInterface;

class FileResult implements ResultInterface
{

    private ?string $name = null;
    private string $content;

    public function __construct(
        string $name,
        string $content,
    )
    {
        $name = $this->normalizeFileName($name);
        $this->name = $name;
        $this->content = $content;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    private function normalizeFileName(string $fileName): string
    {
        $fileName1 = realpath($fileName);
        if (!empty($fileName1)) {
            $fileName = $fileName1;
        }
        return $fileName;
    }
}