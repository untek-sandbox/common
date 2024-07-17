<?php

/**
 * @var View $view
 * @var CollectionData $collectionData
 * @var PaginationWidget $paginationWidget
 * @var LogItem[] $list
 * @var array $filter
 * @var array $queryParams
 * @var array $dates
 * @var string $baseUrl
 * @var string[] $channels
 * @var TranslatorInterface $translator
 */

use Untek\Component\LogReader\Domain\Model\LogItem;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Component\Web\TwBootstrap\Widgets\Pagination\PaginationWidget;
use Untek\Component\Web\View\Libs\View;
use Untek\Model\DataProvider\Dto\CollectionData;
use Monolog\Level;

$levels = [];
foreach (Level::NAMES as $name) {
    $levelIndex = Level::fromName($name);
    $levels[$levelIndex->value] = $levelIndex->name;
}

$rowClasses = [
    Level::Emergency->value => 'table-danger',
    Level::Alert->value => 'table-danger',
    Level::Critical->value => 'table-danger',
    Level::Error->value => 'table-danger',
    Level::Warning->value => 'table-warning',
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        Log list
    </title>
</head>
<body>
<h1>
    Log list
</h1>

<form class="form-inline" method="get">
    <div class="form-group mx-sm-3 mb-2">
        <label for="inputDate" class="sr-only">Date</label>
        <select class="form-control" id="inputDate" name="filter[date]" title="Date">
            <?php foreach ($dates as $index => $name): ?>
                <option value="<?= $name ?>" <?= (!empty($filter['date']) && $filter['date'] == $name) ? 'selected="selected"' : '' ?>>
                    <?= $name ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <label for="inputChannel" class="sr-only">Channel</label>
        <select class="form-control" id="inputChannel" name="filter[channel]" title="Channel">
            <option value="">- Select channel -</option>
            <?php foreach ($channels as $index => $name): ?>
                <option value="<?= $name ?>" <?= (!empty($filter['channel']) && $filter['channel'] == $name) ? 'selected="selected"' : '' ?>>
                    <?= $name ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <label for="inputLevel" class="sr-only">Level</label>
        <select class="form-control" id="inputLevel" name="filter[level]" title="Level">
            <option value="">- Select level -</option>
            <?php foreach ($levels as $index => $name): ?>
            <option value="<?= $index ?>" <?= (!empty($filter['level']) && $filter['level'] == $index) ? 'selected="selected"' : '' ?>>
                <?= $name ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <label for="inputPageSize" class="sr-only">PageSize</label>
        <select class="form-control" id="inputPageSize" name="page[size]" title="Page size">
            <?php foreach ([10, 30, 50, 100] as $size): ?>
            <option value="<?= $size ?>" <?= ($collectionData->getPage()->getPageSize() == $size) ? 'selected="selected"' : '' ?>>
                <?= $size ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <label for="inputMessage" class="sr-only">Message</label>
        <input class="form-control" id="inputMessage" name="filter[message]" title="Message" placeholder="Message" value="<?= $filter['message'] ?? '' ?>"/>
    </div>
    <button type="submit" class="btn btn-primary mb-2">Filter</button>
    &nbsp;
    <a class="btn btn-secondary mb-2" href="/log">Reset</a>
    <div class="form-group mx-sm-3 mb-2">
        Items: <?= $collectionData->getPage()->getItemsTotalCount() ?>
        |
        Pages: <?= $collectionData->getPage()->getPageCount() ?>
    </div>
</form>

<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Level</th>
        <th scope="col">Channel</th>
        <th scope="col">Message</th>
        <th scope="col">Time</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($collectionData->getCollection() as $item):
        /** @var LogItem $item */
        $rowClass = $rowClasses[$item->getLevel()] ?? '';
        $url = $baseUrl . '/' . $item->getId() . '?' . http_build_query($queryParams);
        ?>
        <tr class="<?= $rowClass ?>">
            <th>
                <a href="<?= $url ?>">
                    <?= $item->getId() ?>
                </a>
            </th>
            <td>
                <a href="<?= $url ?>">
                    <?= $item->getLevelName() ?>
                </a>
            </td>
            <td>
                <a href="<?= $url ?>">
                    <?= $item->getChannel() ?>
                </a>
            </td>
            <td>
                <small>
                    <a href="<?= $url ?>">
                        <?= isset($filter['message']) ? str_replace($filter['message'], '<b>' . $filter['message'] . '</b>', $item->getMessage()) : $item->getMessage() ?>
                    </a>
                </small>
            </td>
            <td>
                <a href="<?= $url ?>">
                    <?= $item->getCreatedAt()->format('H:i:s') ?>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $paginationWidget->run() ?>

</body>
</html>