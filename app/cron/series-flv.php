<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 14/10/2017
 * Time: 13:51
 */

set_time_limit(0);

include "../autoload.php";

$seriesList =
    [
       /*['slug'=>'456/los-simpson-1989','_seasons'=>[2],'url'=>'http://seriesflv.net/{slug}/seasons/{season}']*/
        '456'=>['_seasons'=>[1]]
    ];

$i=0;
$postUrl ="http://seriesflv.net/tpl";
foreach ($seriesList as $id => $serie)
{
    $data["data"]["id"]=$id;
    $data["name"]= "list_episodes";
    $data["type"]="template";
    $data["both"]="0";

    foreach ($serie["_seasons"] as $season)
    {
        $data["data"]["season"]=$season;

      $res = CurlService::post($postUrl,$data);
        $html = hQuery::fromHTML($res["data"]["content"]);

        $episodios = $html->find(".row");

        $i=1;
        foreach ($episodios as $k=>$v)
        {

            $linksUrl ="http://olimpo.link/?server=www.rapidvideo.com&limit=15&tmdb=456&season={$season}&episode={$i}";

            $html = CurlService::get($linksUrl);

            $html = hQuery::fromHTML($html);

            $href= $html->find(".file-results .title a")->attr("href");

            $html = CurlService::get($href);

            $html = hQuery::fromHTML($html);

            $iframeSrc ="http://olimpo.link".$html->find("iframe")->attr("src");

            var_dump($iframeSrc);
            $html = CurlService::get("http://olimpo.link/anon-ref.php?to=p4uV4FLu0EXWkalIgLyjGKFiaI%2F8gNkmolPDBqCucOI%2BZTTfmuhya%2FQzQxiynKDG%2F7532bGm6HD5d5%2BkMUQzqbaA8i973NuoLUCmYybB3AMpsDsDQs4yUNyyu%2BBuo0hCa5tTWNwTYHeY4dIjA5mQ2Y3TcQV7uv%2BXA9S3Xv5XBkA%3D");

            echo $html;


            exit();
            $i++;
        }

    }




}