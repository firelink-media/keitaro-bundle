<?php

namespace TdsProviderBundle\Utils;

class UrlUtils
{
    public static function getUrlPathFromUrl(string $url): string
    {
        return parse_url($url, PHP_URL_PATH);
    }
}
