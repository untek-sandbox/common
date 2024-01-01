<?php

namespace Untek\Bundle\Notify\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Model\Validator\Interfaces\ValidationByMetadataInterface;

class FlashEntity implements ValidationByMetadataInterface
{

    private $type = null;

    private $message = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

    }

    public function setType($value)
    {
        $this->type = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setMessage($value)
    {
        $this->message = $value;
    }

    public function getMessage()
    {
        return $this->message;
    }

}
