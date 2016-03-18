<?php
/**
*  Create On 2010-7-12
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/

namespace BF;

include_once('Base.class.php');
abstract class Controller extends \BF\Base {
	
	/**
	 * 控制页面跳转
	 * @param  $cName 控制器名
	 * @param  $aName 方法名
 	 * @param  $time  停留时间
	 * @param  $message 显示信息
	 * @return 空
	 */
	public function gotoUrl($url, $time = 0, $message = "") {
		$this -> getApp() -> gotoUrl($url, $time, $message);
	}
	
	public function error404 ($msg = null) {
		$this -> getApp() -> error404($msg);
	}

}