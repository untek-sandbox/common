<?php

namespace Untek\Component\Web\Controller\Traits;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait ControllerUrlGeneratorTrait
{

    private $urlGenerator;
    private $baseRoute;

    public function getUrlGenerator(): ?UrlGeneratorInterface
    {
        return $this->urlGenerator;
    }

    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator): void
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getBaseRoute(): string
    {
        return $this->baseRoute;
    }

    public function setBaseRoute(string $baseRoute): void
    {
        $this->baseRoute = $baseRoute;
    }

    public function generateUrl(string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        if($this->getUrlGenerator()) {
            return $this->getUrlGenerator()->generate($name, $parameters, $referenceType);
        }
        if($this->getLayoutManager()) {
            return $this->getLayoutManager()->generateUrl($name, $parameters, $referenceType);
        }
    }
}
