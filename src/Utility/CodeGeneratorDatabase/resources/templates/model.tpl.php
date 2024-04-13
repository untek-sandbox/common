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

<?php foreach ($properties as $attribute): ?>
<?= $attribute->generate() ?>

<?php endforeach; ?>

<?php foreach ($properties as $attribute):
    $propertyName = $attribute->getName();
    $propertyType = $attribute->getType()->generate();
    ?>
    public function get<?= ucfirst($propertyName) ?>(): <?= $propertyType ?>

    {
        return $this-><?= $propertyName ?>;
    }

    public function set<?= ucfirst($propertyName) ?>(<?= $propertyType ?> $<?= $propertyName ?>): void
    {
        $this-><?= $propertyName ?> = $<?= $propertyName ?>;
    }

<?php endforeach; ?>
}