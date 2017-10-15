<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 12/10/2017
 * Time: 10:52 AM
 */

try
{
    include ("../autoload.php");

    $act = empty($_GET['act'])?"":$_GET['act'];

    $res = [];
    switch ($act)
    {
        case 'link':

            $id = (!empty($_GET["id"]))?$_GET["id"]:false;

            $yt =YoutubeDLService::getLink($id);

            $res = ['src'=>end($yt)];

            break;
        case 'list':

            $q = (!empty($_GET["q"]))?$_GET["q"]:false;
            $p = (!empty($_GET["p"]))?$_GET["p"]:false;

            $res= YoutubeDLService::find($q,$p);


            break;
    }

    echo json_encode($res);
}
catch (Exception $e)
{
    if(!empty($_LANG[$e->getMessage()]))
    {
        http_response_code ($e->getCode());
        echo $_LANG[$e->getMessage()];
    }
    else
    {
        http_response_code (500);
        echo  $_LANG["error"];
    }
}