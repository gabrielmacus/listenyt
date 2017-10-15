<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 14/10/2017
 * Time: 14:31
 */
class VideoService
{
    static function getLink($id=false)
    {
        if(empty($id))
        {
            throw new Exception("youtube.error.idNotSpecified",400);
        }

        $proxy= CurlService::get("https://gimmeproxy.com/api/getProxy?protocol=socks4&country=AR");

        $proxy = json_decode($proxy,true);

        $proxy ="{$proxy["protocol"]}://{$proxy["ip"]}:{$proxy["port"]}/";

        $cmd  = "{$_ENV['youtube']['cmd']} -g {$id} --proxy {$proxy}";

        $otp = [];

        $code=false;

        exec($cmd,$otp,$code);

        if(!count($otp))
        {
            throw new \Exception("youtube.error.linkNotFetched",500);
        }

        return $otp;
    }
}