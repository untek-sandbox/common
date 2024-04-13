<?php

namespace Untek\Component\Web\WebApp\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Untek\Core\App\Enums\AppEventEnum;
use Untek\Core\App\Events\AppEvent;
use Untek\Component\Web\WebApp\Libs\EnvDetector\WebEnvDetector;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class WebDetectTestEnvSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            AppEventEnum::BEFORE_INIT_ENV => 'onBeforeInitEnv',
        ];
    }

    public function onBeforeInitEnv(AppEvent $event)
    {
        $request = $event->getApp()->getRequest();
        $envDetector = new WebEnvDetector($request);
        $isTest = $envDetector->isTest();
        $mode = $isTest ? 'test' : 'main';
        $event->getApp()->setMode($mode);
    }
}
