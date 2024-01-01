<?php

namespace Untek\Kaz\Eds\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Domain\Validator\Interfaces\ValidationByMetadataInterface;
use Untek\Domain\Entity\Interfaces\UniqueInterface;
use Untek\Domain\Entity\Interfaces\EntityIdInterface;

class LogEntity implements ValidationByMetadataInterface, UniqueInterface, EntityIdInterface
{

    private $id = null;

    private $hostId = null;

    private $createdCount = null;

    private $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
//        $metadata->addPropertyConstraint('id', new Assert\NotBlank);
        $metadata->addPropertyConstraint('hostId', new Assert\NotBlank);
        $metadata->addPropertyConstraint('createdCount', new Assert\NotBlank);
        $metadata->addPropertyConstraint('createdCount', new Assert\PositiveOrZero());
        $metadata->addPropertyConstraint('createdAt', new Assert\NotBlank);
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

    public function setCreatedCount($value) : void
    {
        $this->createdCount = $value;
    }

    public function getCreatedCount()
    {
        return $this->createdCount;
    }

    public function setCreatedAt($value) : void
    {
        $this->createdAt = $value;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }


}

