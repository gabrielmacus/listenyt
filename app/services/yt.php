<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 12/10/2017
 * Time: 11:25 AM
 */

class YoutubeDLService
{
   //https://gimmeproxy.com/#how
    /**
     * @deprecated Now you should user the function from VideoService class
     * @param bool $id
     * @return array
     * @throws Exception
     */
    static function getLink($id=false)
    {
        if(empty($id))
        {
            throw new Exception("video.error.idNotSpecified",400);
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
            throw new \Exception("video.error.linkNotFetched",500);
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

        $q= urlencode($q);

        $url ="https://www.youtube.com/results?search_query={$q}&p={$p}";


        $data = CurlService::get($url);

        $html = hQuery::fromHTML($data);


        $result = [];

        $videos =$html->find(".yt-lockup-video");

        foreach ($videos as $v)
        {


            $id = explode("=",$v->find("a",0)->href);



            if(count($id)>1)
            {
                $id =$id[1];
                $result[$id]["title"]=$v->find(".yt-lockup-title a")->text();
                $result[$id]["thumbnail"]["mq"] = "http://img.youtube.com/vi/{$id}/mqdefault.jpg";
                $result[$id]["thumbnail"]["hq"] = "https://img.youtube.com/vi/{$id}/maxresdefault.jpg";
                $result[$id]["duration"] =$v->find(".yt-thumb-simple .video-time")->text();
                $result[$id]["user"] = $v->find(".yt-lockup-byline a")->text();
                $result[$id]["userLink"] = $v->find(".yt-lockup-byline a")->attr("href");

            }
        }


        return $result;
      


    }
}

