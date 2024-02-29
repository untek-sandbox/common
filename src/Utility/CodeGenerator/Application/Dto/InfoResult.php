<?php

namespace Untek\Utility\CodeGenerator\Application\Dto;

use Untek\Utility\CodeGenerator\Application\Interfaces\ResultInterface;

class InfoResult implements ResultInterface
{

    private ?string $name = null;
    private string $content;

    public function __construct(
        string $name,
        string $content,
    )
    {
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

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}