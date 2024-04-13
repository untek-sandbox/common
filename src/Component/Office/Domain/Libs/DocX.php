<?php

namespace Untek\Component\Office\Domain\Libs;

use App\Common\Base\BaseController;
use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Component\Zip\Libs\Zip;

class DocX
{

    private $zip;

    public function __construct(string $filename)
    {
        $this->zip = new Zip($filename);
    }

    public function replace(array $replacementList)
    {
        $document = $this->zip->readFile('word/document.xml');
        $document = TemplateHelper::render($document, $replacementList, '{{', '}}');
        $this->zip->writeFile('word/document.xml', $document);
    }

    public function close()
    {
        $this->zip->close();
    }
}
