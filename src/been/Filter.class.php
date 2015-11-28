<?php
/**
 *  Create On 2013-7-8
 *  Author Been
 *  QQ:281443751
 *  
 **/

class Filter {
	protected $_app = null;
	protected $_request =null;
	public function doFilter(){
		
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
			exit("File App.class.php no exists!");
		}
	}
	
	public function getRequest(){
		if (null !== $this->_request) {
			return $this->_request;
		}
		return $this->getApp()->getRequest();
	}
	
	public function getCName(){
		return $this->getRequest()->cName;
	}
	
	public function getAName(){
		return $this->getRequest()->aName;
	}

}

?>