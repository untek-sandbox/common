<?php

namespace Untek\Component\Office\Domain\Libs;

use App\Common\Base\BaseController;
use Symfony\Component\Filesystem\Filesystem;

class DocXRender
{

    public function render(string $templateFileName, string $targetFileName, array $replacementList)
    {
        $fs = new Filesystem();
        $fs->remove($targetFileName);
        $fs->mkdir(dirname($targetFileName));
        $fs->copy($templateFileName, $targetFileName);

        $docx = new DocX($targetFileName);
        $docx->replace($replacementList);
        $docx->close();
    }
}
