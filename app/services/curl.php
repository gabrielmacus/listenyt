<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 12/10/2017
 * Time: 12:17 PM
 */

class CurlService
{

    static function get($url)
    {
        $ch = curl_init();
        $timeout = $_ENV['curl']['timeout'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }
}