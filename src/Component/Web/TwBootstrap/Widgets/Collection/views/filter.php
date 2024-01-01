<?php

/**
 * @var HtmlRenderInterface $view
 * @var AttributeEntity[] $attributes
 * @var object $filterModel
 */

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Core\Code\Factories\PropertyAccess;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Component\Http\Helpers\UrlHelper;
use Untek\Component\Web\TwBootstrap\Widgets\Filter\FilterGenerator;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\ActionFormatter;

$propertyAccessor = PropertyAccess::createPropertyAccessor();

$isExists = false;
foreach ($attributes as $attributeEntity) {
    $attributeName = $attributeEntity->getAttributeName();
    $attributeNameCamelCase = Inflector::variablize($attributeName);
    if (property_exists($filterModel, $attributeNameCamelCase)) {
        $isExists = true;
    }
}

?>

<?php if ($isExists): ?>

    <form id="collection-filter-form" action="" method="get">
        <tr>
            <?php foreach ($attributes as $attributeEntity): ?>
                <th>
                    <?php
                    $attributeName = $attributeEntity->getAttributeName();
                    $attributeNameCamelCase = Inflector::variablize($attributeName);
                    if ($attributeEntity->getFormatter()) {
                        $formatter = ClassHelper::createObject($attributeEntity->getFormatter());
                        if ($formatter instanceof ActionFormatter) {
                            echo '
                        <div class="text-right">
                            <a class="text-decoration-none text-primary" href="#" onclick="filterForm.submit(); return false;" title="Send filter parameters">
                                <i class="fas fa-filter"></i>
                            </a>
                            &nbsp;
                            <a class="text-decoration-none text-danger" href="' . UrlHelper::requestUri() . '" title="Clean filter parameters">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>';
                        }
                    }
                    if (property_exists($filterModel, $attributeNameCamelCase)) {
                        $value = $propertyAccessor->getValue($filterModel, $attributeNameCamelCase);
                        $filterDefinition = $attributeEntity->getFilter();
                        $isDefinition = is_array($filterDefinition) && isset($filterDefinition['class']) || is_string($filterDefinition);
                        if ($isDefinition) {
                            echo FilterGenerator::generateByDefinition($filterDefinition, $attributeName, $value);
                        } else {
                            if (!isset($filterDefinition['type'])) {
                                $filterDefinition['type'] = 'text';
                            }
                            echo FilterGenerator::generateByType($filterDefinition, $attributeName, $value);
                        }
                    }
                    ?>
                </th>
            <?php endforeach; ?>
        </tr>
    </form>

    <script>

        window.filterForm = {
            submit: function (self) {
                if (typeof self === 'undefined') {
                    var form = $('#collection-filter-form');
                } else {
                    var form = self.form;
                }
                $(form).submit();
            },
            submitOnKeyDown: function (self, event) {
                if (event.keyCode === 13) {
                    this.submit(self);
                }
            }
        };

    </script>

<?php endif; ?>
