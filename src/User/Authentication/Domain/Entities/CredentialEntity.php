<?php

namespace Untek\User\Authentication\Domain\Entities;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Model\Entity\Interfaces\EntityIdInterface;
use Untek\Model\Entity\Interfaces\UniqueInterface;
use Untek\Model\Validator\Interfaces\ValidationByMetadataInterface;

class CredentialEntity
{

    private $id = null;

    private $identityId = null;

    private $type = null;

    private $credential = null;

    private $validation = null;

    private $createdAt = null;

    private $expiredAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function setId($value) : void
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUserId($value) : void
    {
        $this->identityId = $value;
    }

    public function getUserId()
    {
        return $this->identityId;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function setCredential($value) : void
    {
        $this->credential = $value;
    }

    public function getCredential()
    {
        return $this->credential;
    }

    public function setValidation($value) : void
    {
        $this->validation = $value;
    }

    public function getValidation()
    {
        return $this->validation;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    public function setExpiredAt($expiredAt): void
    {
        $this->expiredAt = $expiredAt;
    }
}
