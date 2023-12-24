<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Dto;

class GenerateResult
{

    private string $fileName;

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }
    
}