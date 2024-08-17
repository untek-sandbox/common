<?php

namespace Untek\Kaz\Eds\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Domain\Validator\Interfaces\ValidationByMetadataInterface;
use Untek\Domain\Entity\Interfaces\UniqueInterface;
use Untek\Domain\Entity\Interfaces\EntityIdInterface;

class CertificateEntity implements ValidationByMetadataInterface, UniqueInterface, EntityIdInterface
{

    private $id = null;

    private $hostId = null;

    private $content = null;

    private $type = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('id', new Assert\NotBlank);
        $metadata->addPropertyConstraint('hostId', new Assert\NotBlank);
        $metadata->addPropertyConstraint('content', new Assert\NotBlank);
        $metadata->addPropertyConstraint('type', new Assert\NotBlank);
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

    public function setHostId($value) : void
    {
        $this->hostId = $value;
    }

    public function getHostId()
    {
        return $this->hostId;
    }

    public function setContent($value) : void
    {
        $this->content = $value;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setType($value) : void
    {
        $this->type = $value;
    }

    public function getType()
    {
        return $this->type;
    }


}

