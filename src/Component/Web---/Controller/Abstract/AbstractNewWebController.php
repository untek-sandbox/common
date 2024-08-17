<?php

namespace Untek\Component\Web\Controller\Abstract;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;
use Untek\Component\Web\Controller\Traits\ControllerUrlGeneratorTrait;
use Untek\Component\Web\Form\Traits\ControllerFormTrait;
use Untek\Component\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;
use Untek\Component\Web\View\Libs\View;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrServiceInterface;

abstract class AbstractNewWebController extends AbstractController
{

    use ControllerUrlGeneratorTrait;
//    use ControllerFormTrait;

    protected $baseUri;
    protected $toastrService;
    protected $breadcrumbWidget;

    /**
     * @required
     */
    #[Required]
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $this->container = $container;
        return $container;
    }

    /**
     * @return ToastrServiceInterface
     * @deprecated
     * @see getLayoutManager()
     */
    public function getToastrService(): ToastrServiceInterface
    {
        return $this->toastrService ?? $this->getLayoutManager()->getToastrService();
    }

    /**
     * @param ToastrServiceInterface $toastrService
     * @deprecated
     * @see getLayoutManager()
     */
    public function setToastrService(ToastrServiceInterface $toastrService): void
    {
        $this->toastrService = $toastrService;
    }

    /**
     * @return ToastrServiceInterface
     * @deprecated
     * @see getLayoutManager()
     */
    public function getBreadcrumbWidget(): BreadcrumbWidget
    {
        return $this->breadcrumbWidget ?? $this->getLayoutManager()->getBreadcrumbWidget();
    }

    /**
     * @param BreadcrumbWidget $breadcrumbWidget
     * @deprecated
     * @see getLayoutManager()
     */
    public function setBreadcrumbWidget(BreadcrumbWidget $breadcrumbWidget): void
    {
        $this->breadcrumbWidget = $breadcrumbWidget;
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        /** @var View $render */
        $render = $this->container->get(View::class);
        $content = $render->renderFile($view, $parameters);
        if (null === $response) {
            $response = new Response();
        }
        $response->setContent($content);
        return $response;
    }

    protected function downloadFile(string $fileName, string $aliasFileName = null): Response
    {
        $aliasFileName = $aliasFileName ?? basename($fileName);
        $content = file_get_contents($fileName);
        return $this->downloadFileContent($content, $aliasFileName);
    }

    protected function downloadFileContent(string $content, string $aliasFileName = null): Response
    {
        $response = new Response($content);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $aliasFileName
        );
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }

    protected function redirectToHome(int $status = 302): RedirectResponse
    {
        return $this->redirect('/', $status);
    }

    protected function redirectToBack(Request $request, string $fallbackUrl = null): RedirectResponse
    {
        $referer = $request->headers->get('referer') ?? $fallbackUrl;
        return new RedirectResponse($referer);
    }
}
