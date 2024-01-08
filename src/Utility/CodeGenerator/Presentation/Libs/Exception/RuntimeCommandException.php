<?php

/*
 * This file is part of the Symfony MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Untek\Utility\CodeGenerator\Presentation\Libs\Exception;

use Symfony\Component\Console\Exception\ExceptionInterface;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

/**
 * An exception whose output is displayed as a clean error.
 *
 * @author Ryan Weaver <ryan@knpuniversity.com>
 */
final class RuntimeCommandException extends \RuntimeException implements ExceptionInterface
{
}
