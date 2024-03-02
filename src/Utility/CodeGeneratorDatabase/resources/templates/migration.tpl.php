<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $tableName
 */

use Untek\Utility\CodeGeneratorApplication\Presentation\Enums\PropertyTypeEnum;
use Untek\Core\Text\Helpers\Inflector;

?>

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Infrastructure\Migration\Abstract\BaseCreateTableMigration;

class <?= $className ?> extends BaseCreateTableMigration
{

    protected $tableName = '<?= Inflector::underscore($tableName) ?>';
    protected $tableComment = '';

    public function tableStructure(Blueprint $table): void
    {
<?php foreach ($properties as $attribute):
    $propertyName = Inflector::underscore($attribute->getName());
    $propertyType = $attribute->getType()->generate();
    ?>
    <?php
    if($propertyName == 'id') {
        echo '      $table->integer(\'id\')->autoIncrement()->comment(\'Идентификатор\');';
    } elseif($propertyType == PropertyTypeEnum::INTEGER) {
        echo '      $table->integer(\''.$propertyName.'\')->comment(\'\');';
    } elseif($propertyType == PropertyTypeEnum::STRING) {
        echo '      $table->string(\''.$propertyName.'\')->comment(\'\');';
    } elseif($propertyType == '\DateTimeImmutable') {
        echo '      $table->dateTime(\''.$propertyName.'\')->comment(\'\');';
    } else {
        echo '      $table->unknown(\''.$propertyName.'\')->comment(\'\');';
    }
    ?>

<?php endforeach; ?>

    }
}