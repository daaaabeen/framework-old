<?php
/**
*  Create On 2010-7-12
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/
class Controller{
	protected $_app=null;
	protected $_request;
	protected $_lang = null;
	public $view=null;
	public function __construct(){
		$this->view=$this->getApp()->getView();		
		$this->_lang = $this->getApp()->getLang();
		//print_r($this->_lang);	
	}
	
	/**
	 * 获取应用
	 * @return App
	 */
	public function getApp(){
		if (null !== $this->_app) {
            return $this->_app;
        }
        
        if (class_exists('App')) {
            $this->_app = App::getInstance();
            return $this->_app;
        }
        else{
        	exit("没有App.class.php类这个文件");
        }
	}
	
	/**
	 * 
	 * @return Request
	 */
	public function  getRequest(){
		if (null !== $this->_request) {
            return $this->_request;
        }
		return $this->getApp()->getRequest();	
	}
	
	public function getDb(){
		return $this->getApp()->getDb();
	}
	
	public function getView(){
		return $this->getApp()->getView();
	}
	
	/**
	 * 返回APP类中用户自定义存储的数据
	 * @param unknown_type $key
	 */
	public function getData($key){
		return $this->getApp()->getData($key);
	}
	
	/**
	 * 控制页面跳转
	 * @param  $cName 控制器名
	 * @param  $aName 方法名
 	 * @param  $time  停留时间
	 * @param  $message 显示信息
	 * @return 空
	 */
	public function gotoUrl($cName="index",$aName="index",$time=0,$message=""){
		$this->getApp()->gotoUrl($cName,$aName,$time,$message);
	}
	
	public function error404($msg = "404 NO Found!"){
		$this->getApp()->error404($msg);
	}
	

}