<?php
/**
*  
*  Create On 2013-11-14����9:51:32
*  Author lidianbin
*  QQ: 281443751
*  Email: lidianbin@iwind-tech.com
**/
// error_reporting(0);
header("content-type:text/html;charset=utf-8");

date_default_timezone_set('Asia/Shanghai');
define("DIR",dirname(__FILE__));
define("APPNAME","index");
ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.dirname(dirname(DIR))."/src/been");//设置框架所在目录为目录为include_path

include_once "App.class.php";
include_once "Db.php";
include_once "View.class.php";
include_once "Lang.class.php";

$app=App::getInstance();
$app->setConfPath(DIR."/setting");

$app->setDb(new Db($app->loadConf("db")));
$app->setView(new View($app->loadConf("view")));

include_once DIR.'/'.APPNAME.'/filter/indexFilter.php';
$app->setFilter(new indexFilter());

$lang = include_once DIR.'/'.APPNAME.'/lang/langConf.php';
//print_r($lang);
$app->setLang(new Lang($lang));

$app->run(DIR.'/'.APPNAME.'/controllers');
//print_r($_SERVER);