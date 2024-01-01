<?php

namespace Untek\Component\Web\Widget\Base;

use ReflectionClass;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Core\Instance\Helpers\ClassHelper;

use Untek\Component\Web\HtmlRender\Infrastructure\Helpers\RenderHelper;
use Untek\Component\Web\Widget\Interfaces\WidgetInterface2;
use Untek\Core\Text\Helpers\TemplateHelper;

abstract class BaseWidget2 implements WidgetInterface2
{
    
    private ?HtmlRenderInterface $htmlRender = null;

    public function assets(): array
    {
        return [];
    }
    
    public function getHtmlRender(): HtmlRenderInterface
    {
        if($this->htmlRender == null) {
            $this->htmlRender = \Untek\Core\Container\Helpers\ContainerHelper::getContainer()->get(HtmlRenderInterface::class);
            //$this->view = new View();
        }
        return $this->htmlRender;
    }

    public function setHtmlRender(HtmlRenderInterface $htmlRender): void
    {
        $this->htmlRender = $htmlRender;
    }
    
    abstract public function run(): string;

    public static function widget(array $config = []): string
    {
        $config['class'] = get_called_class();
        /** @var self $instance */
        $instance = ClassHelper::createObject($config);
        return $instance->run();
    }

    protected function renderTemplate(string $templateCode, array $params)
    {
        return TemplateHelper::render($templateCode, $params);
    }

    protected function registerAssets() {
        $assets = $this->assets();
        foreach ($assets as $asset) {
            $assetInstance = ClassHelper::createInstance($asset);
            $assetInstance->register($this->getHtmlRender());
        }
    }
    
    /*protected function renderTemplate(string $templateCode, array $params)
    {
        return StringHelper::renderTemplate($templateCode, $params);
    }*/

    public function render(string $relativeViewFileAlias, array $params = [])
    {
        $renderDirectory = RenderHelper::getRenderDirectoryByClass($this);
//        $this->getView()->setRenderDirectory($renderDirectory);
//        dd($renderDirectory . '/' . $relativeViewFileAlias);
        return $this->getHtmlRender()->renderFile($renderDirectory . '/' . $relativeViewFileAlias . '.php', $params);
    }

    /*protected function renderFile(string $viewFile, array $params)
    {
        $view = new View();
        $params['widget'] = $this;
        return $view->getRenderContent($viewFile, $params);
    }*/
}
