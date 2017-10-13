<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 02/10/2017
 * Time: 22:07
 */

define('BASE_PATH',dirname(dirname(__FILE__)));

include (BASE_PATH."/vendor/autoload.php");

if(empty($env) || $env = "DEV")
{
    $env = file_get_contents(BASE_PATH."/app/env/development.json");
    
}
else if ($env = "PROD")
{
    $env = file_get_contents(BASE_PATH."/app/env/production.json");

}

$_ENV = json_decode($env,true);


include ("db/autoload.php");

//For composer
//include (BASE_PATH."/vendor/autoload.php");

include ("lang/autoload.php");

include ("auth/autoload.php");

include ("services/autoload.php");
//For websockets
//include ("websockets/Chat.php");




$db = new ActiveRecord();