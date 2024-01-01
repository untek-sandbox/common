<?php

namespace Untek\Component\Web\Controller\Services;

use SplFileInfo;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Bundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\Component\Http\Enums\HttpStatusCodeEnum;
use Untek\Component\I18Next\Facades\I18Next;
use Untek\Component\Web\Controller\Traits\ControllerUrlGeneratorTrait;
use Untek\Component\Web\Form\Interfaces\BuildFormInterface;
use Untek\Component\Web\Form\Traits\ControllerFormTrait;
use Untek\FrameworkPlugin\HttpLayout\Infrastructure\Libs\LayoutManager;
use Untek\Component\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;

class ControllerView
{
    protected $viewsDir;
    protected $view;
    protected $fileExt = 'php';
    private $layoutManager;
    protected $baseUri;
    protected $toastrService;
    protected $breadcrumbWidget;

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    /*public function getViewsDir(): ?string
    {
        return $this->viewsDir;
    }*/

    public function setViewsDir(string $viewsDir): void
    {
        $this->viewsDir = $viewsDir;
    }

    protected function renderTemplate(string $file, array $params = []): Response
    {
        $content = TemplateHelper::loadTemplate($this->viewsDir . '/' . $file . '.' . $this->fileExt, $params);
        /*if (isset($this->layout)) {
//            $params = ArrayHelper::merge($this->getLayoutParams(), $params);
            $params['content'] = $content;
            $content = TemplateHelper::loadTemplate($this->layout, $params);
        }*/
        return new Response($content);
    }

    protected function getView(): HtmlRenderInterface
    {
        if (empty($this->view)) {
            $this->view = \Untek\Core\Container\Helpers\ContainerHelper::getContainer()->get(HtmlRenderInterface::class);
//            $this->view = new View();
        }
        return $this->view;
    }

    public function renderFile(string $file, array $params = []): Response
    {
        $view = $this->getView();
        /*if($this->viewsDir) {
            $view->setRenderDirectory($this->viewsDir);
        }*/
        $pageContent = $view->renderFile($file, $params);
        return new Response($pageContent);
    }

    public function downloadFile(string $fileName, string $aliasFileName = null): Response
    {
        $aliasFileName = $aliasFileName ?? basename($fileName);
        $content = file_get_contents($fileName);
        return $this->downloadFileContent($content, $aliasFileName);
    }

    public function downloadFileContent(string $content, string $aliasFileName = null): Response
    {
        $response = new Response($content);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $aliasFileName
        );
        $response->headers->set('Content-Disposition', $disposition);
//        dd($response);
        return $response;
    }

    /*public function render(string $file, array $params = []): Response
    {
        $view = $this->getView();
//        $view->setRenderDirectory($this->viewsDir);
        $pageContent = $view->render($file, $params);
        return new Response($pageContent);
    }*/

    public function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        $url = $this->urlGenerator->generate($route, $parameters);
        return $this->redirect($url, $status);
    }

    public function redirectToHome(int $status = 302): RedirectResponse
    {
        return $this->redirect('/', $status);
    }

    public function redirectToBack(Request $request, string $fallbackUrl = null): RedirectResponse
    {
        $referer = $request->headers->get('referer') ?? $fallbackUrl;
        //$request->getSession()->setFlash('error', $exception->getMessage());
        return new RedirectResponse($referer);
    }

    public function redirect(string $url, int $status = HttpStatusCodeEnum::MOVED_TEMPORARILY): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    /*protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }*/
}
