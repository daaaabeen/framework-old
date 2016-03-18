<?php
/**
*  
*  Create On 2013-11-14����9:51:32
*  Author lidianbin
*  QQ: 281443751
*  Email: lidianbin@iwind-tech.com
**/
// error_reporting(0);
//use Been;
header("content-type:text/html;charset=utf-8");

date_default_timezone_set('Asia/Shanghai');
define("DIR",dirname(__FILE__));
define("APPNAME", "index");
ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.dirname(dirname(DIR))."/src/BF");
//设置框架所在目录为目录为include_path

include_once "App.class.php";
include_once "Db.php";
include_once "View.class.php";
include_once "Lang.class.php";

$app=\BF\App::getInstance();
$app->setConfPath(DIR."/setting");

$app->setDb(new \BF\Db($app->loadConf("db")));
$app->setView(new \BF\View($app->loadConf("view")));

// include_once DIR.'/'.APPNAME.'/filter/indexFilter.php';
// $app->setFilter(new indexFilter());

$lang = include_once DIR.'/'.APPNAME.'/lang/langConf.php';
//print_r($lang);
$app->setLang(new \BF\Lang($lang));

$app->run(DIR.'/'.APPNAME.'/controllers', DIR.'/model');
//print_r($_SERVER);