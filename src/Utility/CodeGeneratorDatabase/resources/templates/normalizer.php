<?php

/**
 * @var string $namespace
 * @var string $className
 */

?>

namespace <?= $namespace ?>;

use Untek\Database\Base\Hydrator\DatabaseItemNormalizer;
use ArrayObject;

class <?= $className ?> extends DatabaseItemNormalizer
{

    public function normalize(mixed $object, string $format = null, array $context = []): float|array|ArrayObject|bool|int|string|null
    {
        return parent::normalize($object, $format, $context);
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
<?php foreach ($properties as $attribute){
    $propertyName = $attribute['name'];
    $fieldName = \Untek\Core\Text\Helpers\Inflector::underscore($propertyName);
    $propertyType = $attribute['type'];
    if($propertyType == 'array') {
        echo "\t\t\$data['$fieldName'] = json_decode(\$data['$fieldName'], true);\n";
    }
}?>
        return parent::denormalize($data, $type, $format, $context);
    }
}
