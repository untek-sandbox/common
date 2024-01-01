<?php

namespace Untek\Component\Web\HtmlRender\Infrastructure\Services;

abstract class BaseResource
{

    protected string $code = '';
    protected array $files = [];

    public function registerFile(string $file, array $options = []): void
    {
        $this->files[] = [
            'file' => $file,
            'options' => $options,
        ];
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function resetFiles(): void
    {
        $this->files = [];
    }

    public function registerCode(string $code): void
    {
        $this->code .= PHP_EOL . $code . PHP_EOL;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function resetCode(): void
    {
        $this->code = '';
    }
}
