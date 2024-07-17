<?php

/**
 * @var View $view
 * @var LogItem $entity
 * @var TranslatorInterface $translator
 */

use Untek\Component\LogReader\Domain\Model\LogItem;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Component\Web\TwBootstrap\Widgets\Pagination\PaginationWidget;
use Untek\Component\Web\View\Libs\View;
use Untek\Model\DataProvider\Dto\CollectionData;
use Monolog\Level;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        Log details
    </title>
</head>
<body>
<h1>
    Log details
</h1>

<table class="table table-striped">
    <tbody>
    <tr>
        <th>ID</th>
        <td><?= $entity->getId() ?></td>
    </tr>
    <tr>
        <th>Level name</th>
        <td><?= $entity->getLevelName() ?></td>
    </tr>
    <tr>
        <th>Level code</th>
        <td><?= $entity->getLevel() ?></td>
    </tr>
    <tr>
        <th>Channel</th>
        <td><?= $entity->getChannel() ?></td>
    </tr>
    <tr>
        <th>Message</th>
        <td><?= $entity->getMessage() ?></td>
    </tr>
    <tr>
        <th>Time</th>
        <td><?= $entity->getCreatedAt()->format(DateTime::RSS) ?></td>
    </tr>
    <tr>
        <th>Context</th>
        <td><?php dump($entity->getContext()) ?></td>
    </tr>
    <tr>
        <th>Extra</th>
        <td><?php dump($entity->getExtra()) ?></td>
    </tr>

    </tbody>
</table>

</body>
</html>