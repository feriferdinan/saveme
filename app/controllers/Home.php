<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

use Ayesh\InstagramDownload\InstagramDownload;

class Home extends Controller
{
    public function index()
    {
        $data['title'] = 'Save-Me';
        $data['css'] = ['home/css/index'];
        $data['js'] = ['home/js/index'];
        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer', $data);
    }

    public function instagram($url)
    {
        try {
            $client = new InstagramDownload($url);
            $url = $client->getDownloadUrl(); // Returns the download URL.
            $type = $client->getType(); // Returns "image" or "video" depending on the media type.
            return [
                "status" => true,
                "data" => $url,
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

    public function twitter($url = "")
    {
        require_once './app/core/TwitterDownloader.php';
        $t = new TwitterDownloader($url);
        $result =  $t->download();
        return [
            "status" => true,
            "data" => $result,
            "message" => "Success",
        ];
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
        return [
            "status" => true,
            "type" => "video",
            "message" => "Success",
            "data" => [
                "videoHD" => $videoHD,
                "videoSD" => $videoSD,
            ],
        ];
    }

    public function download()
    {
        $url = $_POST["url"];
        $app = $_POST["app"];
        $result = [];
        switch ($app) {
            case 'instagram':
                $result = $this->instagram($url);
                break;
            case 'twitter':
                $result = $this->twitter($url);
                break;
            case 'facebook':
                $result = $this->facebook_video($url);
                break;
            default:
                break;
        }

        echo json_encode($result);
    }
}
