<?php

namespace Utils;

use PHPUnit\Framework\TestCase;
use SitesPublisher\Utils\UrlUtils;

class UrlUtilsTest extends TestCase
{
    /** @dataProvider provideTestGetUrlPathFromUrl */
    public function testGetUrlPathFromUrl(string $url, string $expected): void
    {
        $actual = UrlUtils::getUrlPathFromUrl($url);

        $this->assertEquals($expected, $actual);
    }

    public function provideTestGetUrlPathFromUrl(): iterable
    {
        yield [
            'url' => 'https://hmkyasinobanladesa.com/welcome-bonuses',
            'expected' => '/welcome-bonuses',
        ];

        yield [
            'url' => 'https://slovakia-bonusesfinder.com/free-spins/?_gl=1*iobb1m*_ga*bHhM',
            'expected' => '/free-spins/',
        ];
    }
}
