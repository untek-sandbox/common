<?php

namespace Untek\User\Authentication\Domain\Entities;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Model\Validator\Interfaces\ValidationByMetadataInterface;
use Untek\Model\Entity\Interfaces\UniqueInterface;
use Untek\Model\Entity\Interfaces\EntityIdInterface;

class TokenEntity implements ValidationByMetadataInterface, UniqueInterface, EntityIdInterface
{

    private $id = null;

    private $identityId = null;

    private $type = null;

    private $value = null;

    private $createdAt = null;

    private $expiredAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTime;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
//        $metadata->addPropertyConstraint('id', new Assert\NotBlank);
        $metadata->addPropertyConstraint('identityId', new Assert\NotBlank);
        $metadata->addPropertyConstraint('type', new Assert\NotBlank);
        $metadata->addPropertyConstraint('value', new Assert\NotBlank);
        $metadata->addPropertyConstraint('createdAt', new Assert\NotBlank);
//        $metadata->addPropertyConstraint('expiredAt', new Assert\NotBlank);
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

    public function setIdentityId($value) : void
    {
        $this->identityId = $value;
    }

    public function getIdentityId()
    {
        return $this->identityId;
    }

    public function setType($value) : void
    {
        $this->type = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setValue($value) : void
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setCreatedAt($value) : void
    {
        $this->createdAt = $value;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setExpiredAt($value) : void
    {
        $this->expiredAt = $value;
    }

    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

}