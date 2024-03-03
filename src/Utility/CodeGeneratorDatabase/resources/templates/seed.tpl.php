<?php

/**
 * @var string $namespace
 * @var string $className
 * @var PropertyGenerator[] $properties
 */

use Laminas\Code\Generator\PropertyGenerator;

?>

namespace <?= $namespace ?>;

class <?= $className ?>

{

    public function generateItems(): array
    {
        $collectionSize = 10;
        $collection = [];
        for ($id = 1; $id <= $collectionSize; $id++) {
            $item = [
<?php foreach ($properties as $attribute){
    $propertyName = $attribute->getName();
    $fieldName = \Untek\Core\Text\Helpers\Inflector::underscore($propertyName);
    $propertyType = $attribute->getType()->generate();
$value = null;
if($propertyName == 'id') {
    $value = "\$id";
} elseif($propertyType == 'int') {
    $value = "\$id";
} elseif($propertyType == 'string') {
    $value = "'qwerty'";
} else {
    $value = "null";
}
    echo "\t\t\t\t'$fieldName' => $value,\n";
}?>
            ];
            $collection[] = $item;
        }
        return $collection;
    }
}