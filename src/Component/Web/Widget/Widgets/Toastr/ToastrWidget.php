<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr;

use Untek\Bundle\Notify\Domain\Entities\ToastrEntity;
use Untek\Bundle\Notify\Domain\Enums\FlashMessageTypeEnum;
use Untek\Bundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use Untek\Component\Web\HtmlRender\Application\Services\JsResourceInterface;
use Untek\Component\Web\Widget\Base\BaseWidget2;
use Untek\Core\Collection\Interfaces\Enumerable;

class ToastrWidget extends BaseWidget2
{
    private $toastrService;

    public $closeButton = false;

    public $debug = false;

    public $newestOnTop = false;

    public $progressBar = true;

    public $positionClass = "toast-bottom-left";

    public $preventDuplicates = false;

    public $onclick = null;

    public $showDuration = "300";

    public $hideDuration = "1000";

    public $timeOut = "5000";

    public $extendedTimeOut = "1000";

    public $showEasing = "swing";

    public $hideEasing = "linear";

    public $showMethod = "fadeIn";

    public $hideMethod = "fadeOut";

    public bool $disableAssets = false;

    private $js;

    public function __construct(ToastrServiceInterface $toastrService, JsResourceInterface $js)
    {
        $this->toastrService = $toastrService;
        $this->js = $js;
    }

    public function assets(): array
    {
        if($this->disableAssets) {
            return [];
        }
        return [
            ToastrAsset::class,
        ];
    }

    public function run(): string
    {
        $this->registerAssets();
        $collection = $this->toastrService->findAll();
        $this->generateHtml($collection);
        return '';
    }

    protected function registerAssets()
    {
        parent::registerAssets();
        $this->js->registerVar('toastr.options', $this);
    }

    private function generateHtml(Enumerable $collection)
    {
        if ($collection->isEmpty()) {
            return;
        }
//        dd($collection);
        /** @var ToastrEntity $entity */
        foreach ($collection as $entity) {
            $type = $entity->getType();
            $type = str_replace('alert-', '', $type);
            $content = $entity->getContent();
            $toastrType = $this->getType($type);
            $content = str_replace([PHP_EOL, '"'], ['\n', '\"'], $content);
            $this->js->registerCode("toastr.{$toastrType}(\"{$content}\"); \n");
        }
        $this->toastrService->clear();
    }

    private function getType(string $type): string {
        $arr = [
            FlashMessageTypeEnum::SUCCESS => 'success',
            FlashMessageTypeEnum::INFO => 'info',
            FlashMessageTypeEnum::WARNING => 'warning',
            FlashMessageTypeEnum::ERROR => 'error',
        ];
        return $arr[$type];
    }
}
