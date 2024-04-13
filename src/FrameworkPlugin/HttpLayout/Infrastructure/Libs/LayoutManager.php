<?php

namespace Untek\FrameworkPlugin\HttpLayout\Infrastructure\Libs;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Bundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use Untek\Component\Translation\Interfaces\Services\TranslationServiceInterface;
use Untek\Component\Web\TwBootstrap\Widgets\Alert\AlertWidget;
use Untek\Component\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;

class LayoutManager
{

    protected $toastrService;
    protected $breadcrumbWidget;
    protected $urlGenerator;
    protected $translator;

    public function __construct(
        ToastrServiceInterface $toastrService,
        BreadcrumbWidget $breadcrumbWidget,
        UrlGeneratorInterface $urlGenerator,
        TranslationServiceInterface $translator
    )
    {
        $this->toastrService = $toastrService;
        $this->breadcrumbWidget = $breadcrumbWidget;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
    }

    public function getToastrService(): ToastrServiceInterface
    {
        return $this->toastrService;
    }

    /*public function setToastrService(ToastrServiceInterface $toastrService): void
    {
        $this->toastrService = $toastrService;
    }*/

    public function getBreadcrumbWidget(): BreadcrumbWidget
    {
        return $this->breadcrumbWidget;
    }

    /*public function setBreadcrumbWidget(BreadcrumbWidget $breadcrumbWidget): void
    {
        $this->breadcrumbWidget = $breadcrumbWidget;
    }*/

    public function addBreadcrumb(string $label, string $routeName, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): void
    {
        $url = $this->urlGenerator->generate($routeName, $parameters, $referenceType);
        $this->getBreadcrumbWidget()->add($label, $url);
    }

    public function addAlert(string $message, string $type = null): void
    {
        AlertWidget::add($message, $type);
    }

    public function getTranslator(): TranslationServiceInterface
    {
        return $this->translator;
    }

    public function toastrSuccess($message, int $delay = null): void
    {
        $this->getToastrService()->success($message, $delay);
    }

    public function toastrInfo($message, int $delay = null)
    {
        $this->getToastrService()->info($message, $delay);
    }

    public function toastrWarning($message, int $delay = null)
    {
        $this->getToastrService()->warning($message, $delay);
    }

    public function toastrError($message, int $delay = null)
    {
        $this->getToastrService()->error($message, $delay);
    }

    public function translate(string $bundleName, string $key, array $variables = [])
    {
        $this->translator->t($bundleName, $key, $variables);
    }

    public function generateUrl(string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->urlGenerator->generate($name, $parameters, $referenceType);
    }
}
