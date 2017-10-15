<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 14/10/2017
 * Time: 13:51
 */

set_time_limit(0);

include "../autoload.php";

/*$proxy = ["type"=>CURLPROXY_SOCKS5,"url"=>"179.159.22.10:32671"];
echo CurlService::get("https://1fgqfmf.oloadcdn.net/dl/l/--An5u2cOkTf2ecV/wlV13dUXZxQ/m3nt3s_cr1m1nn4l3s_1x01_es_vito.avi.mp4?mime=true",$proxy);
*/

$seriesList =
    [
        ['slug'=>'2890-mentes-criminales-criminal-minds-','_seasons'=>[2],'url'=>'http://www.misterseries.com/series/{slug}/seasons/{season}']
    ];

$i=0;
foreach ($seriesList as $k=>$v)
{
    $url = $v["url"];

    $url  = str_replace('{slug}',$v['slug'],$url);

    foreach ($v["_seasons"] as $season)
    {
        $finalUrl  = str_replace('{season}',$season,$url);

        $data= CurlService::get($finalUrl);
        $html = hQuery::fromHTML($data);

       $episodios =  $html->find("#episode-list .media");

       foreach ($episodios as $clave => $valor)
       {
           $i++;
           $episodioUrl  =$valor->find("a")->attr("href");

           $episodioTitle = explode("-",$valor->find(".media-heading a")->text());
           $episodioTitle  =trim(end($episodioTitle));

           if($i==20)
           {
               $data = CurlService::get($episodioUrl);
               $episodiosHtml  = hQuery::fromHTML($data);

               $links =$episodiosHtml->find(".links-table tbody tr");

               $episodio =[];
               foreach ($links as $link)
               {
                   $linkUrl = $link->find("td")->attr("data-bind");

                   $linkUrl = str_replace("'","",trim(explode(",",$linkUrl)[1]));

                   if(strpos($linkUrl,"openload"))
                   {

                       $language = strtolower(substr($link->find("td")->text(),0,1));

                       $episodio["urls"][$language]=$linkUrl;
                       $episodio["title"]=$episodioTitle;


                   }


               }
               $seriesList[$k]["seasons"][$season]['episodes'][$i]= $episodio;

           }
       }

    }


}

unset($seriesList["_seasons"]);
echo json_encode($seriesList);