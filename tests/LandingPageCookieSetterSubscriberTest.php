<?php


use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use TdsProviderBundle\EventSubscriber\LandingPageCookieSetterSubscriber;

class LandingPageCookieSetterSubscriberTest extends TestCase
{
    use ProphecyTrait;

    /** @var ResponseEvent */
    private $event;

    /** @var LandingPageCookieSetterSubscriber */
    private $landingPageCookieSetterSubscriber;

    protected function setUp(): void
    {
        $this->event = $this->prophesize(ResponseEvent::class);
        $this->landingPageCookieSetterSubscriber = new LandingPageCookieSetterSubscriber();
    }

    public function testOnKernelResponseWhenLandingPageCookieNotExist(): void
    {
        $request = new Request([], [], [], [], []);
        $request->server->set('REQUEST_URI', 'https://rudik.com');

        $response = new Response('');

        $this->event->getRequest()->shouldBeCalledOnce()->willReturn($request);
        $this->event->getResponse()->shouldBeCalledOnce()->willReturn($response);

        $this->landingPageCookieSetterSubscriber->onKernelResponse($this->event->reveal());

        $expected = new Cookie(
            'landing_page',
            $request->getUri(),
            (new \DateTimeImmutable())->modify('+2year')
        );

        $this->assertEquals($expected, $response->headers->getCookies()[0]);
    }

    public function testOnKernelResponseWhenLandingPageCookieExist(): void
    {
        $request = new Request([], [], [], ['landing_page' => 'rudik.com'], []);
        $request->server->set('REQUEST_URI', 'https://rudik.com');

        $this->event->getRequest()->shouldBeCalledOnce()->willReturn($request);

        $this->landingPageCookieSetterSubscriber->onKernelResponse($this->event->reveal());
    }
}
