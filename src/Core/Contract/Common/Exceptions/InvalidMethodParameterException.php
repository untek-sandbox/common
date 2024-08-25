<?php

namespace Untek\Core\Contract\Common\Exceptions;

use Exception;

/**
 * Неверный параметр метода
 */
class InvalidMethodParameterException extends Exception
{

    /**
     * Имя неверного параметра
     * @var string
     */
    private $parameterName;

    public function getParameterName(): ?string
    {
        return $this->parameterName;
    }

    public function setParameterName(string $parameterName): self
    {
        $this->parameterName = $parameterName;
        return $this;
    }
}
