<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Dto;

class GenerateResult
{

    private ?string $fileName = null;
    private ?string $code = null;
    private string $type;
    private bool $hasChanges;

    public function __construct(
        string $fileName = null,
        ?string $code = null,
        string $type = 'text',
    )
    {
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

    public function getCode(): string
    {
        return $this->content;
    }

    public function setCode(string $code): void
    {
        $this->content = $code;
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

}