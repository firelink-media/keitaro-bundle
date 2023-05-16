<?php

namespace TdsProviderBundle\Utils;

use GuzzleHttp\Client;

class SlackUtils
{
    public static function sendMessage(string $messageText): bool
    {
        $slackUrl = getenv('SLACK_URL');
        if (!$slackUrl) {
            return false;
        }

        try {
            $client = new Client([
                'timeout' => 5.0,
            ]);
            $message = ['text' => $messageText];
            $client->request('POST', $slackUrl, [
                'json' => $message,
            ]);

            return true;
        } catch (\Throwable $t) {

            return false;
        }
    }
}
