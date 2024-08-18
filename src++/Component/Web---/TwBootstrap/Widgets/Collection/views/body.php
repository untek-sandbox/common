<?php

/**
 * @var HtmlRenderInterface $view
 * @var Enumerable $collection
 * @var AttributeEntity[] $attributes
 * @var FormatEncoder $formatter
 */

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Component\I18Next\Facades\I18Next;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Libs\FormatEncoder;

?>

<?php if (!$collection->isEmpty()): ?>
    <?php foreach ($collection as $entity): ?>
        <tr>
            <?php foreach ($attributes as $attributeEntity):
                $attributeEntity->setEntity($entity);
                $value = $formatter->encode($attributeEntity);
                ?>
                <td><?= $value ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="<?= count($attributes) ?>">
            <i class="text-muted">
                <?= I18Next::t('web', 'message.empty_list') ?>
            </i>
        </td>
    </tr>
<?php endif; ?>
