<?php

class Home extends Controller
{
    const TWITTER_VIDEO_PLAYER_INFO = 'https://twitter.com/i/videos/tweet/';
    const TWITTER_VIDEO_INFO_API = 'https://api.twitter.com/1.1/statuses/show/';
    const INSTAGRAM_DOMAIN = 'instagram.com';
    const TWITTER_DOMAIN = 'twitter.com';
    const FACEBOOK_DOMAIN = 'facebook.com';

    public function index()
    {
        $data['title'] = 'Save-Me';
        $data['js'] = ['home/js/index'];
        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer', $data);
    }

    public function instagram($url = "https://www.instagram.com/p/CFZEwgDAJ5m/?utm_source=ig_web_copy_link")
    {
        try {
            $the_url = explode("?", $url)[0];
            $header[0]  = "Accept: text/xml,application/xml,application/xhtml+xml,";
            $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
            $ip  = '36.86.63.182';
            $header[] = "Cache-Control: max-age=0";
            $header[] = "Connection: keep-alive";
            $header[] = "Keep-Alive: 300";
            $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
            $header[] = "Accept-Language: en-us,en;q=0.5";
            $header[] = "Pragma: "; // browsers = blank
            // $header[] = "X_FORWARDED_FOR: " . $ip;
            // $header[] = "REMOTE_ADDR: " . $ip;
            // $header[] = "Host: https://save-me.herokuapp.com/";
            $res = $this->fetch($the_url . '?__a=1', $header);
            $res_json = json_decode($res);
            $data = [];
            if ($res_json->graphql->shortcode_media->is_video) {
                $data['download_link'] = $res_json->graphql->shortcode_media->video_url;
                $data['type'] = 'video';
            } else {
                $data['download_link'] = $res_json->graphql->shortcode_media->display_url;
                $data['type'] = 'image';
            }
            // $parsed = $this->parse_meta($HTML);
            // $data = $this->process_meta($parsed);

            return [
                "status" => true,
                "data" => $data['download_link'],
                "type" => $data['type'],
                "message" => $data['type'],
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

    private function facebook($url)
    {
        try {
            $HTML = $this->fetch($url);
            $parsed = $this->parse_meta($HTML);
            $data = $this->process_meta($parsed);
            if ($data["type"] == "video") {
                $html_encoded = htmlentities($HTML);
                $videoHD = "";
                $videoSD = "";
                $re = '/hd_src:"(.*?)",sd_src/m';
                preg_match_all($re, $HTML, $matches, PREG_SET_ORDER, 0);
                if ($matches && is_array($matches) && sizeof($matches) > 0) {
                    $matches = $matches[0];
                    if ($matches && is_array($matches) && sizeof($matches) > 1) $videoHD = $matches[1];
                }

                $re = '/sd_src_no_ratelimit:"(.*?)"/m';
                preg_match_all($re, $HTML, $matches, PREG_SET_ORDER, 0);
                if ($matches && is_array($matches) && sizeof($matches) > 0) {
                    $matches = $matches[0];
                    if ($matches && is_array($matches) && sizeof($matches) > 1) $videoSD = $matches[1];
                }
                if ($videoHD || $videoSD) {
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
            } else {
                return [
                    "status" => true,
                    "data" => $data["download_url"],
                    "type" => $data["type"],
                    "message" => $data["type"],
                ];
            }
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



    private function twitter($url = "https://twitter.com/NetflixID/status/1307922507291176960")
    {
        try {
            $urlDetails = $this->extractIdFromUrl($url);

            $bearerToken = $this->getAuthorizationToken($urlDetails, self::TWITTER_VIDEO_PLAYER_INFO);

            $response = $this->fetch(self::TWITTER_VIDEO_INFO_API . $urlDetails['tweetId'] . '.json?include_entities=true', [
                "Authorization: {$bearerToken}"
            ]);

            $jsonResponse = json_decode($response);
            $result = [];
            $text = $jsonResponse->text;
            if (isset($jsonResponse->extended_entities)) {
                $thumbnail = $jsonResponse->extended_entities->media[0]->media_url_https;
                $url = $jsonResponse->extended_entities->media[0]->url;
                $type = $jsonResponse->extended_entities->media[0]->type;

                $variants = [];
                if (isset($jsonResponse->extended_entities->media[0]->video_info->variants))
                    $variants = $jsonResponse->extended_entities->media[0]->video_info->variants;

                $result = compact("text", "thumbnail", "url", "type", "variants");
            }

            if (isset($result)) {
                if ($result["type"] == "video") {
                    return [
                        "status" => true,
                        "type" => "video",
                        "data" => [
                            "videoSD" => $result["variants"][2]->url,
                            "videoHD" => $result["variants"][3]->url,
                        ],
                        "message" => "Success",
                    ];
                } else {
                    return [
                        "status" => true,
                        "type" => "image",
                        "data" => $result["thumbnail"],
                        "message" => "Success",
                    ];
                }
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


    public function download()
    {
        $url = $_POST["url"];
        $result = [];
        $url_parsed = \parse_url($url);
        switch ($url_parsed['host']) {
            case self::INSTAGRAM_DOMAIN:
            case 'www.' . self::INSTAGRAM_DOMAIN:
                $result = $this->instagram($url);
                break;
            case self::TWITTER_DOMAIN:
            case 'www.' . self::TWITTER_DOMAIN:
                $result = $this->twitter($url);
                break;
            case self::FACEBOOK_DOMAIN:
            case 'www.' . self::FACEBOOK_DOMAIN:
                $result = $this->facebook($url);
                break;
            default:
                $result =  [
                    "status" => false,
                    "data" => "",
                    "message" => "Invalid URL ",
                ];
                break;
        }
        $result['host'] =  $url_parsed['host'];
        echo json_encode($result);
    }
}
