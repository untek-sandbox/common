<?php

namespace Untek\Lib\Web\View\Resources;

abstract class BaseResource
{

    protected $code = '';
    protected $files = [];

    public function registerFile(string $file, array $options = [])
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

    public function resetFiles()
    {
        $this->files = [];
    }

    public function registerCode(string $code)
    {
        $this->code .= PHP_EOL . $code . PHP_EOL;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function resetCode()
    {
        $this->code = '';
    }
}
