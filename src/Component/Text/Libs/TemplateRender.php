<?php

namespace Untek\Component\Text\Libs;

use Untek\Component\Text\Helpers\TemplateHelper;

class TemplateRender
{
    protected array $replacement = [];

    public function __construct(private string $beginBlock = '{{', private string $endBlock = '}}')
    {
    }

    public function renderTemplate(string $template): string
    {
        return TemplateHelper::render($template, $this->replacement, $this->beginBlock, $this->endBlock);
    }

    public function addReplacement(string $placeholderName, string $value): self
    {
        $this->replacement[$placeholderName] = $value;
        return $this;
    }

    public function addReplacementList(array $list): self
    {
        $this->replacement = $this->replacement + $list;
        return $this;
    }
}
