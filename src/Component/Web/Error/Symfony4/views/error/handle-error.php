<?php

/**
 * @var string $title
 * @var string $message
 * @var HtmlRenderInterface $view
 * @var Exception $exception
 */

use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

?>

<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading"><?= $title ?></h4>
    <p><?= $message ?></p>
    <?php if(isset($exception)): ?>
        <hr>
        <p>Class: <?= get_class($exception) ?></p>
        <p>File: <?= $exception->getFile() ?>:<?= $exception->getLine() ?></p>
        <pre><p style="font-size: 75% !important;"><?= $exception->getTraceAsString() ?></p></pre>
    <?php endif; ?>
</div>
