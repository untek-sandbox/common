<?php

/**
 * @var HtmlRenderInterface $view
 * @var array $rows
 * @var string $type
 */

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

$type = $type ?? 'td';

?>

<?php if ($rows): ?>
    <?php foreach ($rows as $row): ?>
        <tr>
            <?php foreach ($row as $cell): ?>
                <?php if ($type == 'td'): ?>
                    <td><?= $cell ?></td>
                <?php elseif ($type == 'th'): ?>
                    <th><?= $cell ?></th>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
