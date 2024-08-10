<?php

namespace Untek\Component\Translator\Infrastructure\Subscribers;

use Untek\Component\Translator\Infrastructure\Services\LanguageService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class RestApiLocaleSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private TranslatorInterface $translator,
        private LanguageService $languageService,
        private string $headerKeyName = 'Language',
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 128],
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $language = $event->getRequest()->headers->get($this->headerKeyName);
        if (!empty($language)) {
            $locale = $this->languageService->getLocale($language);
        } else {
            $locale = getenv('TRANSLATOR_DEFAULT_LANGUAGE');
        }
        $this->translator->setLocale($locale);
    }
}
