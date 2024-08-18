<?php

/**
 * @var HtmlRenderInterface $view
 * @var string $baseUrl
 * @var array $queryParams
 * @var AttributeEntity[] $attributes
 */

use Untek\Component\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use Untek\Component\Web\Html\Helpers\HtmlHelper;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

?>

<tr>
    <?php foreach ($attributes as $attributeEntity): ?>
        <th>
            <?php
            if ($attributeEntity->getSortAttribute()) {
                echo \Untek\Component\Web\TwBootstrap\Widgets\Collection\Helpers\CollectionWidgetHelper::sortByField($attributeEntity->getLabel(), $attributeEntity->getSortAttribute(), $baseUrl, $queryParams);
            } else {
                echo $attributeEntity->getLabel();
            }
            ?>
        </th>
    <?php endforeach; ?>
</tr>
