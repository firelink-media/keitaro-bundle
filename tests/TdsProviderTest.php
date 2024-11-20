<?php


namespace TdsProviderBundle\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use TdsProviderBundle\Provider\TdsProvider;
use TdsProviderBundle\Utils\KClickClient;

class TdsProviderTest extends TestCase
{
    private $requestStack;

    private $request;

    private $client;

    public function setUp(): void
    {
        $this->client = $this->createMock(KClickClient::class);
        $this->requestStack = $this->createMock(RequestStack::class);

        $this->request = Request::create(
            'http://example.com',
            'GET',
            [],
            [
                '_ga' => 'ga-value',
                'ref' => 'ref-value',
                'landing_page' => 'http://example.com/page',
                'reflink_click_timestamp' => 'timestamp-value',
                'customer' => '{"uid":"123"}',
            ]
        );

        $this->requestStack->method('getCurrentRequest')->willReturn($this->request);

    }

    public function testDoTdsRequestPassesCorrectParamsToClient()
    {
        $expectedParams = [
            ['site', 'test-host'],
            ['sub_id_1', 'test-type'],
            ['sub_id_2', 'ga-value'],
            ['sub_id_3', 'ref-value'],
            ['sub_id_4', 'http://example.com/page'],
            ['sub_id_5', '/page'],
            ['sub_id_6', 'timestamp-value'],
            ['sub_id_7', '123'],
        ];

        $this->client
            ->expects($this->exactly(8))
            ->method('param')
            ->willReturnCallback(function (string $key, string $value) use (&$expectedParams): void {
                $expectedCall = array_shift($expectedParams);
                TestCase::assertSame($expectedCall, [$key, $value]);
            })
        ;

        $this->client->expects($this->once())->method('sendAllParams');
        $this->client->expects($this->once())->method('currentPageAsReferrer');
        $this->client->expects($this->once())->method('keyword')->with('test-keyword');
        $this->client->expects($this->once())->method('forceRedirectOffer');
        $this->client->expects($this->once())->method('execute')->willReturn('');
        $this->client->expects($this->once())->method('sendHeaders')->willReturn([]);

        $tdsProvider = new TdsProvider(
            $this->requestStack,
            'api.example.com',
            'test-api-key',
            'test-host'
        );
        $tdsProvider->initClient($this->client);

        $tdsProvider->doTdsRequest('test-keyword', 'test-type', $this->request);

        $this->assertEmpty($expectedParams, 'Not all parameters were transferred to the client');
    }
}
