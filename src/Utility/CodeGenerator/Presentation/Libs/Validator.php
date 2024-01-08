<?php

/*
 * This file is part of the Symfony MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Untek\Utility\CodeGenerator\Presentation\Libs;

use Doctrine\Common\Persistence\ManagerRegistry as LegacyManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Untek\Framework\Console\Infrastructure\Validators\BooleanValidator;
use Untek\Framework\Console\Infrastructure\Validators\ClassExistsValidator;
use Untek\Framework\Console\Infrastructure\Validators\ClassNameValidator;
use Untek\Framework\Console\Infrastructure\Validators\ClassNotExistsValidator;
use Untek\Framework\Console\Infrastructure\Validators\EmailValidator;
use Untek\Framework\Console\Infrastructure\Validators\EnglishValidator;
use Untek\Framework\Console\Infrastructure\Validators\IsNumericValidator;
use Untek\Framework\Console\Infrastructure\Validators\LengthValidator;
use Untek\Framework\Console\Infrastructure\Validators\NotBlankValidator;
use Untek\Framework\Console\Infrastructure\Validators\PrecisionValidator;
use Untek\Framework\Console\Infrastructure\Validators\PropertyNameValidator;
use Untek\Framework\Console\Infrastructure\Validators\ScaleValidator;
use Untek\Framework\Console\Infrastructure\Exceptions\RuntimeCommandException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Ryan Weaver <weaverryan@gmail.com>
 *
 * @internal
 */
final class Validator
{
    public static function validateClassName(?string $className, string $errorMessage = ''): string
    {
        return ClassNameValidator::validate($className, $errorMessage);
    }

    public static function isEnglish(string $value = null): string
    {
        return EnglishValidator::validate($value);
    }

    public static function isNumeric(string|int $value = null): string
    {
        return IsNumericValidator::validate($value);
    }

    public static function notBlank(string $value = null): string
    {
        return NotBlankValidator::validate($value);
    }

    public static function validateLength($length)
    {
        return LengthValidator::validate($length);
    }

    public static function validatePrecision($precision)
    {
        return PrecisionValidator::validate($precision);
    }

    public static function validateScale($scale)
    {
        return ScaleValidator::validate($scale);
    }

    public static function validateBoolean($value)
    {
        return BooleanValidator::validate($value);
    }

    public static function validatePropertyName(string $name): string
    {
        return PropertyNameValidator::validate($name);
    }

    public static function validateEmailAddress(?string $email): string
    {
        return EmailValidator::validate($email);
    }

    public static function existsOrNull(string $className = null, array $entities = []): ?string
    {
        if (null !== $className) {
            self::validateClassName($className);

            if (str_starts_with($className, '\\')) {
                self::classExists($className);
            } else {
                self::entityExists($className, $entities);
            }
        }

        return $className;
    }

    public static function classExists(string $className, string $errorMessage = ''): string
    {
        return ClassExistsValidator::validate($className);
    }

    public static function classDoesNotExist($className): string
    {
        return ClassNotExistsValidator::validate($className);
    }
}
