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
<?php if($modelClassName): ?>
use <?= $modelClassName ?>;
<?php endif; ?>

class <?= $className ?> implements RestApiSchemaInterface
{

    public function encode(mixed $data): mixed
    {
<?php if($modelClassName): ?>
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
<?php else: ?>
        $item = PropertyHelper::toArray($data);
<?php endif; ?>
        return $item;
    }
}
