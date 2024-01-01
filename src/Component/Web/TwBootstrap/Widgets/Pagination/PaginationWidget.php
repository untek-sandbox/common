<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Pagination;

use Symfony\Component\HttpFoundation\Request;
use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Model\DataProvider\Entities\DataProviderEntity;
use Untek\Model\DataProvider\Libs\DataProvider;
use Untek\Component\Web\Widget\Base\BaseWidget2;
use Untek\Component\Web\TwBootstrap\Widgets\Menu\MenuWidget;

class PaginationWidget extends BaseWidget2
{

    /** @var DataProvider */
    public $dataProvider;
    /** @var DataProviderEntity */
    private $dataProviderEntity;
    private $request;
    private $perPageOptions = [10, 20, 50];
    private $showPages = 7;
    private $leftArrowHtml = '&laquo;';
    private $rightArrowHtml = '&raquo;';

    public $linkTemplate = '<a href="{url}" class="page-link {class}">{label}</a>';
    public $layoutTemplate = '
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end">
                {items}
            </ul>
        </nav>';
    public $pageSizeWrapperTemplate = '
        <li class="page-item ">
            <a class="page-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {pageSize}
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <h6 class="dropdown-header">Page size</h6>
                {items}
            </div>
        </li>';
    public $pageSizeItemTemplate = '<a class="dropdown-item" href="{url}">{size}</a>';

    public function __construct(DataProvider $dataProvider = null/*, Request $request = null*/)
    {
        $request = /*$request ?: */
            Request::createFromGlobals();
        $this->request = $request;
    }

    public function getShowPages(): int
    {
        return $this->showPages;
    }

    public function setShowPages(int $showPages): void
    {
        $isEven = $showPages % 2 == 0;
        if($isEven) {
            $showPages = $showPages + 1;
        }
        $this->showPages = $showPages;
    }

    public function init()
    {
        $this->dataProviderEntity = $this->dataProvider->getEntity();
        $this->dataProviderEntity->setTotalCount($this->dataProvider->getTotalCount());
    }

    public function run(): string
    {
        $this->init();
        if ($this->dataProviderEntity->getPageCount() == 1) {
            return '';
        }
        $itemsHtml = $this->renderItems();
        $renderPageSizeSelector = $this->renderPageSizeSelector();
        $itemsHtml .= $renderPageSizeSelector/* ? '<li class="page-item">' . $renderPageSizeSelector . '</li>' : ''*/
        ;
        return $this->renderLayout($itemsHtml);
    }

    private function generateUrl(int $page = 1)
    {
        $queryParams = $this->request->query->all();
        $queryParams['per-page'] = $this->dataProviderEntity->getPageSize();
        $queryParams['page'] = $page;
        $queryString = http_build_query($queryParams);
        return '?' . $queryString;
    }

    private function generateItemsData(int $pageStart, int $pageEnd)
    {
        $items = [];
        for ($page = $pageStart; $page <= $pageEnd; $page++) {
            $isActive = $this->dataProviderEntity->getPage() == $page;
            $items[] = $this->generateItem($page);
            /*$items[] = [
                'label' => $page,
                'url' => $this->generateUrl($page),
                'active' => $isActive ? 'active' : '',
            ];*/
        }
        return $items;
    }

    private function generateItem(int $page, $label = null, bool $isDisable = false): array
    {
        $isActive = $this->dataProviderEntity->getPage() == $page && $isDisable == false;
        return [
            'label' => $label ?: $page,
            'url' => $this->generateUrl($page),
            'encode' => false,
            'active' => $isActive ? 'active' : '',
            'options' => [
                'class' => ($isDisable ? 'page-item disabled' : 'page-item'),
                'title' => $page,
            ],
        ];
    }

    private function renderItems()
    {

        /*$spliter = [
            'label' => '...',
            'url' => '',
            'encode' => false,
            'options' => ['class' => 'page-item disabled'],
        ];*/

        $pageStart = 1;
        $pageCount = $this->dataProviderEntity->getPageCount();
        $pageEnd = $pageCount;

        $showPages = $this->getShowPages();

        $jumpStep = $showPages;

        $page = $this->dataProviderEntity->getPage();
        $showPagesHalf = intval(floor($showPages / 2));

        $pageStart = $page - $showPagesHalf;
        if ($pageStart < 1) {
            $pageStart = 1;
        }

        $pageEnd = $page + $showPagesHalf;
        if ($pageEnd > $pageCount) {
            $pageEnd = $pageCount;
        }

        $items = [];

        $prevPage = $this->dataProviderEntity->getPrevPage() > 0 ? $this->dataProviderEntity->getPrevPage() : 1;
        $items[] = $this->generateItem($prevPage, $this->leftArrowHtml, $this->dataProviderEntity->isFirstPage());

        /*$items[] = [
            'label' => '&laquo;',
            'url' => $this->generateUrl($this->dataProviderEntity->getPrevPage()),
            'encode' => false,
            'options' => ['class' => ($this->dataProviderEntity->isFirstPage() ? 'page-item disabled' : 'page-item')],
        ];*/

        if ($pageStart > 1) {

            $jumpPrev = $page - $jumpStep;
            if ($jumpPrev <= 1) {
                $jumpPrev = 2;
            }

            $items[] = $this->generateItem(1);

            /*$items[] = [
                'label' => '1',
                'url' => $this->generateUrl(1),
                'encode' => false,
            ];*/

            if ($pageStart > 2) {
                $items[] = $this->generateItem($jumpPrev, '...');
                /*$items[] = [
                    'label' => '...',
//                    'label' => '&laquo; ' . $jumpPrev . ' &raquo;',
                    'url' => $this->generateUrl($jumpPrev),
                    'encode' => false,
                ];*/
            }
        }

        $its = $this->generateItemsData($pageStart, $pageEnd);
        $items = array_merge($items, $its);

        if ($pageEnd < $pageCount) {

            $jumpNext = $page + $jumpStep;
            if ($jumpNext >= $pageCount) {
                $jumpNext = $pageCount - 1;
            }

            if ($pageEnd < $pageCount - 1) {
                $items[] = $this->generateItem($jumpNext, '...');
                /*$items[] = [
                    'label' => '...',
//                    'label' => '&laquo; ' . $jumpNext . ' &raquo;',
                    'url' => $this->generateUrl($jumpNext),
                    'encode' => false,
                    'options' => [
                        'title' => $jumpNext,
                    ],
                ];*/
            }

            $items[] = $this->generateItem($pageCount);

            /*$items[] = [
                'label' => $pageCount,
                'url' => $this->generateUrl($pageCount),
                'encode' => false,
            ];*/
        }

        $nextPage = $this->dataProviderEntity->getNextPage() < $pageCount ? $this->dataProviderEntity->getNextPage() : $pageCount;
        $items[] = $this->generateItem($nextPage, $this->rightArrowHtml, $this->dataProviderEntity->isLastPage());

        /*$items[] = [
            'label' => '&raquo;',
            'url' => $this->generateUrl($this->dataProviderEntity->getNextPage()),
            'encode' => false,
            'options' => ['class' => ($this->dataProviderEntity->isLastPage() ? 'page-item disabled' : 'page-item')],
        ];*/

        $menuWidget = new MenuWidget();
        $menuWidget->items = $items;
        $menuWidget->itemOptions = [
            'class' => 'page-item',
        ];
        $menuWidget->linkTemplate = $this->linkTemplate;
        $itemsHtml = $menuWidget->render();
        return $itemsHtml;
    }

    private function renderLayout(string $items)
    {
        return TemplateHelper::render($this->layoutTemplate, ['items' => $items]);
    }

    private function renderPageSizeSelector()
    {
        if (empty($this->perPageOptions)) {
            return '';
        }
        $html = '';
        $queryParams = $this->request->query->all();
        foreach ($this->perPageOptions as $size) {
            $queryParams['per-page'] = $size;
            $queryParams['page'] = 1;
            $queryString = '?' . http_build_query($queryParams);
            $html .= TemplateHelper::render($this->pageSizeItemTemplate, [
                'url' => $queryString,
                'size' => $size,
            ]);
        }
        return TemplateHelper::render($this->pageSizeWrapperTemplate, [
            'pageSize' => $this->dataProviderEntity->getPageSize(),
            'items' => $html,
        ]);
    }
}