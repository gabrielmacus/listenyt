<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 12/10/2017
 * Time: 11:25 AM
 */
class YoutubeDLService
{
   
    static function getLink($id=false)
    {
        if(empty($id))
        {
            throw new Exception("youtube.error.idNotSpecified",400);
        }


        $proxy ="https://216.56.48.118:9000/";

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

    static function find($q = false,$p=1)
    {

        if(empty($q))
        {
            throw new Exception("youtube.error.queryDataNotSpecified",400);
        }

        if(!is_numeric($p))
        {
            $p = 1;
        }

        $url ="https://www.youtube.com/results?search_query={$q}&p={$p}";


        $data = CurlService::get($url);
        $html = str_get_html($data);

        $result = [];

        $videos =$html->find(".yt-lockup-video");

        foreach ($videos as $v)
        {


            $id = explode("=",$v->find("a",0)->href);



            if(count($id)>1)
            {
                $id =$id[1];
                $result[$id]["title"]=$v->find(".yt-lockup-title a",0)->innertext;
                $result[$id]["thumbnail"]["mq"] = "http://img.youtube.com/vi/{$id}/mqdefault.jpg";
                $result[$id]["thumbnail"]["hq"] = "https://img.youtube.com/vi/{$id}/maxresdefault.jpg";
                $result[$id]["duration"] =$v->find(".yt-thumb-simple .video-time",0)->innertext;
                $result[$id]["user"] = $v->find(".yt-lockup-byline a",0)->innertext;
                $result[$id]["userLink"] = $v->find(".yt-lockup-byline a",0)->href;

            }
        }


        return $result;


    }
}

