<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
class Controller
{
    public function view($view, $data = [])
    {
        require_once './app/views/' . $view . '.php';
    }

    public function model($model)
    {
        require_once './app/models/' . $model . '.php';
        return new $model;
    }

    public function uri_segments($index)
    {
        $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_segments = explode('/', $uri_path);

        return $uri_segments[$index];
    }

    public function does_url_exists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }
    public function fetch(string $url, array $headers = [], $data = []): string
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
        if ($data) {
            \curl_setopt_array($curl, array(
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data
            ));
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

    public function parse_meta($HTML)
    {
        $raw_tags = [];
        $meta_values = [];

        \preg_match_all('/<meta[^>]+="([^"]*)"[^>]' . '+content="([^"]*)"[^>]+>/i', $HTML, $raw_tags);
        if (!empty($raw_tags)) {
            $multi_value_tags = \array_unique(\array_diff_assoc($raw_tags[1], \array_unique($raw_tags[1])));
            foreach ($raw_tags[1] as $i => $tag) {
                $has_multiple_values = false;


                foreach ($multi_value_tags as $multi_tag) {
                    if ($tag === $multi_tag) {
                        $has_multiple_values = true;
                    }
                }

                if ($has_multiple_values) {
                    $meta_values[$tag][] = $raw_tags[2][$i];
                } else {
                    $meta_values[$tag] = $raw_tags[2][$i];
                }
            }
        }

        if (empty($meta_values)) {
            return false;
        }

        return $meta_values;
    }

    public function validateUrl($url, $domain)
    {

        $url = \parse_url($url);
        if (empty($url['host'])) {
            throw new \InvalidArgumentException('Invalid URL');
        }

        $url['host'] = \strtolower($url['host']);

        if ($url['host'] !== $domain && $url['host'] !== 'www.' . $domain) {
            throw new \InvalidArgumentException('Entered URL is not an ' . $domain . ' URL.');
        }

        if (empty($url['path'])) {
            throw new \InvalidArgumentException('No image or video found in this URL');
        }

        $args = \explode('/', $url['path']);
        if (!empty($args[1]) && ($args[1] === 'p' || $args[1] === 'tv') && isset($args[2]{
            4}) && !isset($args[2]{
            255})) {
            return $args[2];
        }

        throw new \InvalidArgumentException('No image or video found in this URL');
    }
    public function extractIdFromUrl($url)
    {
        $tweetUrl = explode('?', $url)[0];
        $tweetUser = explode('/', $tweetUrl)[3];
        $tweetId = explode('/', $tweetUrl)[5];
        return compact("tweetUrl", "tweetUser", "tweetId");
    }

    public function process_meta($meta)
    {
        if (!\is_array($meta)) {
            throw new \RuntimeException('Error fetching information. Perhaps the post is private.', 3);
        }
        if (!empty($meta['og:video'])) {
            return [
                "type" => 'video',
                'download_url' => $meta['og:video']
            ];
        } elseif (!empty($meta['og:image'])) {
            return [
                "type" => 'image',
                'download_url' => $meta['og:image']
            ];
        } else {
            throw new \RuntimeException('Error fetching information. Perhaps the post is private.', 4);
        }
    }

    public function getAuthorizationToken($urlDetails, $api)
    {
        $url = $api . $urlDetails['tweetId'];

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
}
