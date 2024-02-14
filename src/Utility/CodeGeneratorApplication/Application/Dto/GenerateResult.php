<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Dto;

class GenerateResult
{

    const TYPE_FILE = 'file';
    const TYPE_OTHER = 'other';

    private ?string $fileName = null;
    private string $code;

    public function __construct(
        string $fileName,
        string $code,
    )
    {
        $fileName = $this->normalizeFileName($fileName);
        $this->fileName = $fileName;
        $this->code = $code;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getCode(): string
    {
        return $this->code;
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