<?php
/**
*  
*  Create On 2013-9-22锟斤拷锟斤拷4:41:34
*  Author lidianbin
*  QQ: 281443751
*  Email: lidianbin@iwind-tech.com
**/
//error_reporting(0);
date_default_timezone_set('Asia/Shanghai');
define("DIR",dirname(__FILE__));
define("APPNAME","admin");
ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.DIR."/been");//璁剧疆妗嗘灦鎵�湪鐩綍涓虹洰褰曚负include_path

include_once "App.class.php";
include_once "Db.php";
include_once "View.class.php";
include_once "Lang.class.php";

$app=App::getInstance();
$app->setConfPath(DIR."/setting");

$app->setDb(new Db($app->loadConf("db")));

$app->setView(new View($app->loadConf("view")));

include_once DIR.'/'.APPNAME.'/filter/appFilter.php';
$app->setFilter(new appFilter());

$lang = include_once DIR.'/'.APPNAME.'/lang/langConf.php';
//print_r($lang);
$app->setLang(new Lang($lang));


$app->run(DIR.'/'.APPNAME.'/controllers');
//print_r($_SERVER);