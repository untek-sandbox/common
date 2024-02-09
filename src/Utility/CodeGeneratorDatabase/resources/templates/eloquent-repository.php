<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $normalizerClassName
 */

use Untek\Utility\CodeGeneratorApplication\Presentation\Enums\PropertyTypeEnum;
use Laminas\Code\Generator\PropertyGenerator;
use Untek\Core\Text\Helpers\Inflector;

?>

namespace <?= $namespace ?>;

use <?= $interfaceClassName ?>;
use <?= $modelClassName ?>;
use <?= $normalizerClassName ?>;
use Untek\Database\Eloquent\Infrastructure\Abstract\AbstractEloquentCrudRepository;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class <?= $className ?> extends AbstractEloquentCrudRepository implements <?= $className ?>Interface
{

    public function getTableName(): string
    {
        return '<?= $tableName ?>';
    }

    public function getClassName(): string
    {
        return <?= Inflector::camelize($tableName) ?>::class;
    }

    protected function getNormalizer(): NormalizerInterface|DenormalizerInterface
    {
        return new <?= \Untek\Core\Instance\Helpers\ClassHelper::getClassOfClassName($normalizerClassName) ?>();
    }
}