<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var PropertyGenerator[] $properties
 */

use Untek\Utility\CodeGeneratorApplication\Presentation\Enums\PropertyTypeEnum;
use Laminas\Code\Generator\PropertyGenerator;

?>

namespace <?= $namespace ?>;

use Symfony\Component\Validator\Constraint;
use Untek\Model\Validator\Libs\AbstractObjectValidator;
use Symfony\Component\Validator\Constraints as Assert;

class <?= $className ?> extends AbstractObjectValidator
{

    public function getConstraint(): Constraint
    {
        return new Assert\Collection([
            'fields' => [
<?php foreach ($properties as $attribute):
    $propertyName = $attribute->getName();
    $propertyType = $attribute->getType()->generate();
    $reflection = new \Laminas\Code\Reflection\PropertyReflection($attribute->getType(), 'nullable');
    $nullable = $reflection->getValue($attribute->getType());
//    dd($nullable);
    ?>
                '<?= $propertyName ?>' => [
                    new Assert\NotBlank(),
                    <?php
                    if($propertyType == PropertyTypeEnum::INTEGER) {
                        echo 'new Assert\Positive(),';
                        echo PHP_EOL;
                        echo '                    new Assert\Type(\'integer\'),';
                    } elseif($propertyType == PropertyTypeEnum::STRING) {
                        echo 'new Assert\Length(null, 1, 255),';
                        echo PHP_EOL;
                        echo '                    new Assert\Type(\'string\'),';
                    }
                    ?>

                ],
<?php endforeach; ?>
            ]
        ]);
    }
}