<?php
class IndexController extends Controller {
	
	public function __construct() {
		parent::__construct ();
		$this->view->web_host = $this->getRequest ()->hostUrl;
		$this->view->web_app_url = $this->getRequest ()->hostUrl . "/index.php";
	}
	
	public function Index() {
		$this->getView()->web_action = "index";//全部
		$picture = new picture();
		$piclist = $picture->getfrontpic(1);
		//print_r($piclist);
		$this->getView()->picList = $piclist;
		
		
		$act = new activity();
		$this->getView()->typeList = $act->getTypeList();
		if( $userinfo = $this->getData("userinfo") ){
			$userid = $userinfo["id"];
		}else{
			$userid = -1;
		}
		
		$actList = $act->getactivitylist(0, 2, 0, 1, 8 ,$userid);
		//print_r($actList);
		$this->getView()->actList = $actList;
		
		
		$this->getView()->display("index.html");
	}
	
}