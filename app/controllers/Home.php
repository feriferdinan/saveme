<?php



class Home extends Controller
{
    public function index()
    {
        $data['title'] = 'Save-Me';
        $data['js'] = ['home/js/index'];
        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer', $data);
    }

    public function instagram($url)
    {
        require_once './app/core/InstagramDownload.php';
        try {
            $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0';
            $client = new InstagramDownload($url);
            $url = $client->getDownloadUrl(); // Returns the download URL.
            $type = $client->getType(); // Returns "image" or "video" depending on the media type.
            $url_parsed = \parse_url($url);
            return [
                "status" => true,
                "data" => $url,
                "host" => $url_parsed["host"],
                "type" => $type,
                "message" => $type,
            ];
        } catch (\InvalidArgumentException $exception) {
            return [
                "status" => false,
                "data" => "",
                "message" => $exception->getMessage(),
            ];
        } catch (\RuntimeException $exception) {
            return [
                "status" => false,
                "data" => "",
                "message" => $exception->getMessage(),
            ];
        }
    }

    public function instastory($username = "berlliana.lovell")
    {
        require_once './app/core/InstaStory.php';
        $i = new InstaStory();
        $i->getStory($username);
    }

    public function twitter($url = "https://twitter.com/i/status/1306767468971646977")
    {
        require_once './app/core/TwitterDownload.php';
        try {
            $t = new TwitterDownload();
            $result =  $t->download($url);
            if (isset($result["variants"])) {
                $url_parsed = \parse_url($url);
                return [
                    "status" => true,
                    "type" => "video",
                    "host" => $url_parsed["host"],
                    "data" => [
                        "videoSD" => $result["variants"][2]->url,
                        "videoHD" => $result["variants"][3]->url,
                    ],
                    "message" => "Success",
                ];
            } else {
                return [
                    "status" => false,
                    "data" => "",
                    "message" => "Cannot Find Video ",
                ];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return [
                "status" => false,
                "data" => "",
                "message" => "Link is not valid, Example: https://twitter.com/i/status/1306767468971646977",
            ];
        }
    }

    public function facebook_video($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U;   Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $html_encoded = htmlentities($data);
        $videoHD = "";
        $videoSD = "";
        $re = '/hd_src:"(.*?)",sd_src/m';
        preg_match_all($re, $data, $matches, PREG_SET_ORDER, 0);
        if ($matches && is_array($matches) && sizeof($matches) > 0) {
            $matches = $matches[0];
            if ($matches && is_array($matches) && sizeof($matches) > 1) $videoHD = $matches[1];
        }

        $re = '/sd_src_no_ratelimit:"(.*?)"/m';
        preg_match_all($re, $data, $matches, PREG_SET_ORDER, 0);
        if ($matches && is_array($matches) && sizeof($matches) > 0) {
            $matches = $matches[0];
            if ($matches && is_array($matches) && sizeof($matches) > 1) $videoSD = $matches[1];
        }
        $url_parsed = \parse_url($url);
        if ($videoHD || $videoSD) {
            return [
                "status" => true,
                "type" => "video",
                "host" => $url_parsed["host"],
                "message" => "Success",
                "data" => [
                    "videoHD" => $videoHD,
                    "videoSD" => $videoSD,
                ],
            ];
        } else {
            return [
                "status" => false,
                "host" => $url_parsed["host"],
                "message" => "Cannot Find Video",
                "data" => "",
            ];
        }
    }


    public function download()
    {
        $url = $_POST["url"];
        $result = [];
        $url_parsed = \parse_url($url);
        switch ($url_parsed['host']) {
            case 'instagram.com':
            case 'www.instagram.com':
                $result = $this->instagram($url);
                break;
            case 'twitter.com':
            case 'www.twitter.com':
                $result = $this->twitter($url);
                break;
            case 'facebook.com':
            case 'www.facebook.com':
                $result = $this->facebook_video($url);
                break;
            default:
                break;
        }

        echo json_encode($result);
    }
}
