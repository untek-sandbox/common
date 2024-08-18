<?php

namespace Untek\Kaz\Eds\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Domain\Validator\Interfaces\ValidationByMetadataInterface;
use Untek\Domain\Entity\Interfaces\UniqueInterface;
use Untek\Domain\Entity\Interfaces\EntityIdInterface;

class HostEntity implements ValidationByMetadataInterface, UniqueInterface, EntityIdInterface
{

    private $id = null;
    
    private $title = null;

    private $crlUrl = null;
    
    private $caUrl = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
//        $metadata->addPropertyConstraint('id', new Assert\NotBlank);
        $metadata->addPropertyConstraint('title', new Assert\NotBlank);
        $metadata->addPropertyConstraint('crlUrl', new Assert\NotBlank);
    }

    public function unique() : array
    {
        return [];
    }

    public function setId($value) : void
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function setCrlUrl($value) : void
    {
        $this->crlUrl = $value;
    }

    public function getCrlUrl()
    {
        return $this->crlUrl;
    }

    public function getCaUrl()
    {
        return $this->caUrl;
    }

    public function setCaUrl($caUrl): void
    {
        $this->caUrl = $caUrl;
    }
    
}
