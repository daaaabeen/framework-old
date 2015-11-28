<?php
/**
*  Create On 2010-8-21
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/

function __autoload($classname){
	require_once (DIR."/model/".$classname . ".php");
}