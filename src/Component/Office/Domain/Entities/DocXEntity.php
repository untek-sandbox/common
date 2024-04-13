<?php

namespace Untek\Component\Office\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Domain\Validator\Interfaces\ValidationByMetadataInterface;
use Untek\Domain\Entity\Interfaces\UniqueInterface;
use Untek\Domain\Entity\Interfaces\EntityIdInterface;

class DocXEntity implements ValidationByMetadataInterface, UniqueInterface, EntityIdInterface
{

    private $id = null;

    private $fileName = null;

    private $props = null;

    private $word = null;

    private $rels = null;

    private $types = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('id', new Assert\NotBlank);
        $metadata->addPropertyConstraint('fileName', new Assert\NotBlank);
        $metadata->addPropertyConstraint('props', new Assert\NotBlank);
        $metadata->addPropertyConstraint('word', new Assert\NotBlank);
        $metadata->addPropertyConstraint('rels', new Assert\NotBlank);
        $metadata->addPropertyConstraint('types', new Assert\NotBlank);
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

    public function setFileName($value) : void
    {
        $this->fileName = $value;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setProps($value) : void
    {
        $this->props = $value;
    }

    public function getProps()
    {
        return $this->props;
    }

    public function setWord($value) : void
    {
        $this->word = $value;
    }

    public function getWord()
    {
        return $this->word;
    }

    public function setRels($value) : void
    {
        $this->rels = $value;
    }

    public function getRels()
    {
        return $this->rels;
    }

    public function setTypes($value) : void
    {
        $this->types = $value;
    }

    public function getTypes()
    {
        return $this->types;
    }


}

