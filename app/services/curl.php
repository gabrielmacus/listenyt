<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 12/10/2017
 * Time: 12:17 PM
 */

class CurlService
{

    static function post($url,$data,$json= true)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
           http_build_query($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);
        if($json)
        {
            $server_output = json_decode($server_output,true);
        }

        return $server_output;

    }
    static function get($url,$proxy = false)
    {
        $ch = curl_init();
        $timeout = $_ENV['curl']['timeout'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);

        if(!empty($proxy))
        {

            curl_setopt($ch, CURLOPT_PROXY, $proxy["url"]);     // PROXY details with port
            if(!empty($proxy["auth"]))
            {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy["auth"]);   // Use if proxy have username and password

            }
            curl_setopt($ch, CURLOPT_PROXYTYPE, $proxy["type"]); // If expected to call with specific PROXY type

        }


        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }
}