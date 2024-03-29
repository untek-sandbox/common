<?php

namespace Untek\User\Authentication\Domain\Forms;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Component\I18Next\Facades\I18Next;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Model\Validator\Interfaces\ValidationByMetadataInterface;
use Untek\Component\Web\Form\Interfaces\BuildFormInterface;

DeprecateHelper::hardThrow();

class AuthForm implements ValidationByMetadataInterface
{

    private $login;
    private $password;
    private $rememberMe = false;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('login', new Assert\NotBlank);
        $metadata->addPropertyConstraint('password', new Assert\NotBlank);
    }



    protected function t(string $bundle, string $key): string {
        return $bundle . '-' . $key;
//        return I18Next::t($bundle, $key);
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = trim($login);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = trim($password);
    }

    public function getRememberMe(): bool
    {
        return $this->rememberMe;
    }

    public function setRememberMe(bool $rememberMe): void
    {
        $this->rememberMe = $rememberMe;
    }
}