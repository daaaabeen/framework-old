<?php
/**
*  Create On 2010-7-12
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/
class IndexController extends Controller{
	public function __construct(){
		
		parent::__construct();
		//print_r($this->getRequest());
		//$this->view->web_url=$this->getRequest()->hostUrl;
		$this->view->web_host = $this->getRequest()->hostUrl;
		$this->view->web_app_url = $this->getRequest()->hostUrl."/zcmanage.php";
		
	}
	public function Index(){
		//echo "dddddd";
		echo $this->view->render("index.htm");
	}
	public function Header(){
		$userinfo = $this->getData("userinfo");
		$this->view->user = $userinfo['admin_realname'];
		echo $this->view->render("header.htm");
	}
	public function Menu(){
		$resource = $this->getData("resource");
		//print_r($resource);
		$menu =array();
		$menu['Picture'] = 0;
		$menu['Activity'] = 0;
		$menu['News'] = 0;
		$menu['User'] = 0;
		$menu['Stat'] = 0;
		$menu['Knowledge'] = 0;
		$menu['Exercisetype'] = 0;
		$menu['Teacher'] = 0;
		$menu['Push'] = 0;
		foreach($resource as $key => $value ){
			if(array_key_exists($key, $menu)){
				$menu[$key]=1;
			}
		}
		$this->view->menu = $menu;
		echo $this->view->render("menu.htm");
	}
	public function Main(){
		echo $this->view->render("main.htm");
	}
}