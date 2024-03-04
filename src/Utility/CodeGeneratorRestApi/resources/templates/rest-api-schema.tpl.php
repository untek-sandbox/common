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
use <?= $modelClassName ?>;

class <?= $className ?> implements RestApiSchemaInterface
{

    public function encode(mixed $data): mixed
    {
        /** @var <?= \Untek\Core\Instance\Helpers\ClassHelper::getClassOfClassName($modelClassName) ?> $data */
        $item = [
<?php foreach ($properties as $attribute):
    $propertyName = $attribute['name'];
    $propertyType = $attribute['type'];
    $camelCaseName = \Untek\Core\Text\Helpers\Inflector::camelize($propertyName);
    $lcCamelCaseName = lcfirst($camelCaseName);
    ?>
    <?php
    echo "\t\t'$lcCamelCaseName' => \$data->get{$camelCaseName}(),\n";
    ?>
<?php endforeach; ?>
        ];
        return $item;
    }
}
