<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $commandFullClassName
 */

?>

namespace <?= $namespace ?>;

use Untek\Framework\RestApi\Presentation\Http\Symfony\Interfaces\RestApiSchemaInterface;
use Untek\Core\Instance\Helpers\PropertyHelper;

class <?= $className ?> implements RestApiSchemaInterface
{

    public function encode(mixed $data): mixed
    {
        return PropertyHelper::toArray($data);
    }
}
