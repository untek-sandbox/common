<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Dto;

class GenerateResult
{

    const TYPE_FILE = 'file';
    const TYPE_OTHER = 'other';

    private ?string $fileName = null;
    private ?string $code = null;
    private string $type;
    private bool $hasChanges;

    public function __construct(
        string $fileName,
        ?string $code = null,
        string $type = self::TYPE_FILE,
    )
    {
        if ($type == self::TYPE_FILE) {
            $fileName = $this->normalizeFileName($fileName);
        }
        $this->fileName = $fileName;
        $this->code = $code;
        $this->type = $type;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function isHasChanges(): bool
    {
        return $this->hasChanges;
    }

    public function setHasChanges(bool $hasChanges): void
    {
        $this->hasChanges = $hasChanges;
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