<?php

namespace Untek\Component\LogReader\Presentation\Http\Site\Controllers;

use Untek\Component\LogReader\Infrastructure\Persistence\JsonFile\LogRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Untek\Component\App\Services\ControllerAccessChecker;
use Untek\Component\Web\Controller\Abstract\AbstractWebController;
use Untek\Component\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;
use Untek\Component\Web\View\Libs\View;

class GetLogByIdController extends AbstractWebController
{

    protected string $templateFile = __DIR__ . '/../../../../resources/templates/view.php';

    public function __construct(
        View $view,
        BreadcrumbWidget $breadcrumbWidget,
        private ControllerAccessChecker $accessChecker,
    )
    {
        $this->view = $view;
        $this->breadcrumbWidget = $breadcrumbWidget;
    }

    public function __invoke(int $id, string $date, Request $request): Response
    {
        $this->accessChecker->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->breadcrumbWidget->add('Log', '/log');

        $queryParams = $request->query->all();

        if($queryParams) {
            $this->breadcrumbWidget->add('By filter', '/log?' . http_build_query($queryParams));
        }
        $this->breadcrumbWidget->add('#' . $id, '/log/' . $date . '/' . $id);

        $directory = getenv('LOG_DIRECTORY');
        $path = $directory . '/' . $date . '.log';
        $logRepository = new LogRepository($path, $directory);
        $logItem = $logRepository->find($id);

        if (getenv('APP_ENV') === 'prod') {
            return new Response('Main page');
        } else {
            return $this->render([
                'backUrl' => '/log/' . $date,
                'entity' => $logItem,
            ]);
        }
    }
}
