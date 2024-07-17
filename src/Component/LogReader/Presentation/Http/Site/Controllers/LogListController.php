<?php

namespace Untek\Component\LogReader\Presentation\Http\Site\Controllers;

use Forecast\Map\Core\CarPark\Application\Queries\GetCarParkListQuery;
use Untek\Component\LogReader\Infrastructure\Persistence\JsonFile\DateRepository;
use Untek\Component\LogReader\Infrastructure\Persistence\JsonFile\LogRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Untek\Component\App\Services\ControllerAccessChecker;
use Untek\Component\Web\Controller\Abstract\AbstractWebController;
use Untek\Component\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;
use Untek\Component\Web\TwBootstrap\Widgets\Pagination\PaginationWidget;
use Untek\Component\Web\View\Libs\View;
use Untek\Framework\RestApi\Presentation\Http\Symfony\Helpers\QueryParameterHelper;
use Untek\Model\DataProvider\DataProvider;

class LogListController extends AbstractWebController
{

    protected string $templateFile = __DIR__ . '/../../../../resources/templates/log-list.php';

    public function __construct(
        View $view,
        BreadcrumbWidget $breadcrumbWidget,
        private ControllerAccessChecker $accessChecker,
    )
    {
        $this->view = $view;
        $this->breadcrumbWidget = $breadcrumbWidget;
    }

    public function __invoke(Request $request): Response
    {
        $this->accessChecker->denyAccessUnlessGranted('ROLE_ADMIN');

        $query = new GetCarParkListQuery();
        QueryParameterHelper::fillQueryFromRequest($request, $query);
        QueryParameterHelper::removeEmptyFilters($query);

        $directory = getenv('LOG_DIRECTORY');
        $repo = new DateRepository($directory);

        $date = !empty($query->getFilter()['date']) ? $query->getFilter()['date'] : null;
        if ($date === null) {
            $date = $repo->findLast();
        }

        $this->breadcrumbWidget->add('Log', '/log');

        $path = $directory . '/' . $date . '.log';
        $logRepository = new LogRepository($path, $directory);
        $dataProvider = new DataProvider($logRepository);

        $dates = $repo->findAll();

        $collectionData = $dataProvider->findAll($query);
        $paginationWidget = new PaginationWidget($collectionData);

        if (getenv('APP_ENV') === 'prod') {
            return new Response('Main page');
        } else {
            return $this->render([
                'filter' => $query->getFilter(),
                'paginationWidget' => $paginationWidget,
                'collectionData' => $collectionData,
                'baseUrl' => '/log/' . $date,
                'dates' => $dates,
                'queryParams' => $request->query->all(),
                'channels' => [
                    'rest-api',
                    'site',
                    'console',
                ],
            ]);
        }
    }
}
