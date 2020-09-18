
<?php
// require_once './vendor/autoload.php';


class TwitterDownload
{
    const VIDEO_PLAYER_INFO = 'https://twitter.com/i/videos/tweet/';
    const VIDEO_INFO_API = 'https://api.twitter.com/1.1/statuses/show/';

    public function extractIdFromUrl($url)
    {
        // https://twitter.com/SpotifyBR/status/1306242545001914368

        $tweetUrl = explode('?', $url)[0];
        $tweetUser = explode('/', $tweetUrl)[3];
        $tweetId = explode('/', $tweetUrl)[5];

        return compact("tweetUrl", "tweetUser", "tweetId");
    }

    private function fetch(string $url, array $headers = []): string
    {
        $curl = \curl_init($url);

        if (!$curl) {
            throw new \RuntimeException('Unable to initialize curl.', 12);
        }

        \curl_setopt($curl, \CURLOPT_FAILONERROR, true);
        \curl_setopt($curl, \CURLOPT_FOLLOWLOCATION, true);
        \curl_setopt($curl, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($curl, \CURLOPT_TIMEOUT, 1500);

        if (!empty($headers)) {
            \curl_setopt($curl, \CURLOPT_HTTPHEADER, $headers);
        }
        \curl_setopt($curl, \CURLOPT_USERAGENT,  "Mozilla/5.0 (Windows; U;   Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

        // if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        //     \curl_setopt($curl, \CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // }

        \curl_setopt($curl, \CURLOPT_SSL_VERIFYPEER, false);

        \curl_setopt($curl, \CURLOPT_HEADER, false);
        \curl_setopt($curl, \CURLOPT_CONNECTTIMEOUT, 120);

        $response = \curl_exec($curl);

        \curl_close($curl);

        if (!empty($response) && \is_string($response)) {
            return $response;
        }

        throw new \RuntimeException('Could not fetch data.');
    }

    private function getAuthorizationToken($urlDetails)
    {
        $url = self::VIDEO_PLAYER_INFO . $urlDetails['tweetId'];

        $response = $this->fetch($url);

        if (\preg_match('/src="(.*)"/', $response, $matches)) {
            $fileJsUrl = $matches[1];

            $html = $this->fetch($fileJsUrl);

            if (\preg_match('/Bearer ([a-zA-Z0-9%-])+/', $html, $matches)) {
                $bearerToken = $matches[0];

                return $bearerToken;
            }
        }
    }

    public function download($url)
    {
        $urlDetails = $this->extractIdFromUrl($url);

        $bearerToken = $this->getAuthorizationToken($urlDetails);

        $response = $this->fetch(self::VIDEO_INFO_API . $urlDetails['tweetId'] . '.json?include_entities=true', [
            "Authorization: {$bearerToken}"
        ]);

        // if (empty($response))
        //     throw new Exception('Error when fetch data');

        $jsonResponse = json_decode($response);


        $text = $jsonResponse->text;
        if (isset($jsonResponse->extended_entities)) {
            $thumbnail = $jsonResponse->extended_entities->media[0]->media_url_https;
            $url = $jsonResponse->extended_entities->media[0]->url;
            $type = $jsonResponse->extended_entities->media[0]->type;

            $variants = [];
            if (isset($jsonResponse->extended_entities->media[0]->video_info->variants))
                $variants = $jsonResponse->extended_entities->media[0]->video_info->variants;

            return compact("text", "thumbnail", "url", "type", "variants");
        }

        $type = "text";
        return compact("text", "type");
    }
}
