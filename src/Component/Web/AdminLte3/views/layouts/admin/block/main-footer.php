<?php

use Packages\Application\Facades\BrandFacade;

?>

<!-- To the right -->
<div class="float-right d-none d-sm-inline">
    <strong>
        Copyright &copy; 2014-2019.
    </strong>
    All rights reserved.
</div>

<!-- Default to the left -->
<small class="text-muted">
    <?= round(microtime(true) - $_SERVER['MICRO_TIME'], 3) ?>
</small>
