<?php

namespace Untek\Lib\QrBox\Entities;

class FileEntity
{

    private $name;
    private $extension;
    private $mimeType;
    private $content;
    private $size;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getSize()
    {
        if($this->size) {
            return $this->size;
        }
        return mb_strlen($this->getContent());
    }

    public function setSize($size): void
    {
        $this->size = $size;
    }
    
}