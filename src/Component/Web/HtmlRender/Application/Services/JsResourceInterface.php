<?php

namespace Untek\Component\Web\HtmlRender\Application\Services;

interface JsResourceInterface
{
    public function render(): string;

    public function registerFile(string $file, array $options = []): void;

    public function getFiles(): array;

    public function resetFiles(): void;

    public function registerCode(string $code): void;

    public function getCode(): string;

    public function resetCode(): void;

    public function registerVar(string $name, $value): void;
}
