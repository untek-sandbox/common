<?php

namespace Untek\Kaz\Eds\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Domain\Validator\Interfaces\ValidationByMetadataInterface;
use Untek\Domain\Entity\Interfaces\UniqueInterface;
use Untek\Domain\Entity\Interfaces\EntityIdInterface;

class CrlEntity implements ValidationByMetadataInterface, UniqueInterface, EntityIdInterface
{

    private $id = null;
    
    private $hostId = null;

    private $key = null;

    private $revokedAt = null;

    private $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
//        $metadata->addPropertyConstraint('id', new Assert\NotBlank);
        $metadata->addPropertyConstraint('hostId', new Assert\NotBlank);
        $metadata->addPropertyConstraint('key', new Assert\NotBlank);
        $metadata->addPropertyConstraint('revokedAt', new Assert\NotBlank);
    }

    public function unique() : array
    {
        return [
            ['host_id', 'key'],
        ];
    }

    public function setId($value) : void
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHostId()
    {
        return $this->hostId;
    }

    public function setHostId($hostId): void
    {
        $this->hostId = $hostId;
    }

    public function setKey($value) : void
    {
        $this->key = $value;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setRevokedAt($value) : void
    {
        $this->revokedAt = $value;
    }

    public function getRevokedAt()
    {
        return $this->revokedAt;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    
}
