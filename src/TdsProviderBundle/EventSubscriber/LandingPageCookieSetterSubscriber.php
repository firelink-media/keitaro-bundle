<?php

namespace TdsProviderBundle\EventSubscriber;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LandingPageCookieSetterSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        if ($event->getRequest()->cookies->get('landing_page')) {
            return;
        }

        $cookie = new Cookie(
            'landing_page',
            $event->getRequest()->getUri(),
            (new \DateTimeImmutable())->modify('+2year')
        );

        $event->getResponse()->headers->setCookie($cookie);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => [['onKernelResponse']],
        ];
    }
}
