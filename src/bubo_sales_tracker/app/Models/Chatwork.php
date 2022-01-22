<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;
use App\Utils\ClientBuilder;

/**
 * Class Chatwork
 * @package App\Models
 */
class Chatwork
{
    static private $ROOM_ID;
    static private $API_TOKEN;
    static private $ENDPOINT = 'https://api.chatwork.com/v2';
    static private $AllOW_SEND = false;
    static private $APP_VER;

    /**
     * ChatworkNotification constructor.
     */
    public function __construct()
    {
        self::$API_TOKEN = Config::get('app.chatwork.default_token');
        self::$ROOM_ID = Config::get('app.chatwork.default_roomid');
        self::$APP_VER = Config::get('app.app_version');
        if (Config::get("app.env") == "production" || Config::get("app.env") == "staging") {
            self::$AllOW_SEND = true;
        }
    }

    /**
     * @param string $title
     * @param string $body
     * @return mixed
     */
    public function postToLogRoom(string $title, string $body)
    {
        if (self::$AllOW_SEND) {
            $message = ["body" => "[info][title][AppVer:" . self::$APP_VER . "] " . $title . "[/title]" . $body . "[/info]"];

            $response = ClientBuilder::make()
                ->header([
                    "X-ChatWorkToken" => self::$API_TOKEN
                ])
                ->noErrors()
                ->post()
                ->to(self::$ENDPOINT . "/rooms/" . self::$ROOM_ID . "/messages")
                ->with($message)
                ->fire();
        } else {
            $response = json_encode(["message" => "_canSend = false"]);
        }

        return json_decode($response);
    }
}
