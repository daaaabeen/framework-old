<?php
class ActController extends \Been\Controller {
	
	public function __construct() {
		parent::__construct ();
		$this->view->web_host = $this->getRequest ()->hostUrl;
		$this->view->web_app_url = $this->getRequest ()->hostUrl . "/index.php";
	}
	
	public function Index() {
		$this->gotoUrl("Act","Showall");
	}
	public function Showall(){
		//echo "Showall";
		$state = $this->getRequest()->get("state") ? $this->getRequest()->get("state") : "all";
		$order = $this->getRequest()->get("order") ? $this->getRequest()->get("order") : "zan";
		$page = $this->getRequest()->get("page")? $this->getRequest()->get("page") : 1 ;
		$type = $this->getRequest()->get("type") ? $this->getRequest()->get("type") : 0 ; 
		$this->getView()->web_action = "all";//全部
		$this->getView()->state = $state;
		$this->getView()->order = $order;
		$this->getView()->type = $type;
		if($state == "complete"){//完成的
			$state = 2;
		}else if($state == "ongoing"){//进行中的
			$state = 1;
		}else if($state == "failure"){//进行中的
			$state = 3;
		}else{//所有的
			$state = 0;
		}
		
		$act = new activity();
		//$this->getView()->typeList = $act->getTypeList();
		if( $userinfo = $this->getData("userinfo") ){
			$userid = $userinfo["id"];
		}else{
			$userid = -1;
		}
		
		// $this->getView()->typeList = $act->getTypeList();
		// $actList = $act->getactivitylist($type, 2, $state, $page, 16 , $order ,$userid);
		//print_r($actList);
		// $this->getView()->allActNum = $act->getActNum($type, 2, 0);
		// $this->getView()->ongoingActNum = $act->getActNum($type, 2, 1);
		// $this->getView()->completeActNum = $act->getActNum($type, 2, 2);
		// $this->getView()->failureActNum = $act->getActNum($type, 2, 3);
		// $this->getView()->actList = $actList;
		
		$this->getView()->display("showall.html");
	}
	
	/**
	 *  添加新的活动
	 * Enter description here ...
	 */
	public function Add(){
		$this->getView()->web_action = "add";//全部
		$activity = new activity();
		$userinfo = $this->getData("userinfo");
		//print_r($userinfo);
		if($_POST){
			$addactivity = $activity->addact($_POST['name'], $_POST['charge'], $_POST['number'], $_POST['phone'], $_POST['content'],$_POST['intro'], $_POST['picid'], $_POST['price'],$_POST['time'],$_POST['addr'], $_POST['type'], $userinfo['id']);
			if($addactivity){
				$this->gotoUrl('act','success');
				exit();
			}
		}
		echo $this->view->render("add.html");
	}
	
	public function Success(){
		$this->getView()->web_action = "success";
		echo $this->view->render("success.html");
	}
	
	//修改
	public function Modify(){
		$id = intval( $this->getRequest()->get("id") );
		$userinfo = $this->getData("userinfo");
		$userid = $userinfo["id"];
		$act = new activity();

		if($_POST){
			$result = $act->modify($id,$_POST,$userid);
			if($result){
				//echo "chenggong!";
				$this->gotoUrl('act','success');
				exit();
			}else{
				$this->getView()->msg = "修改失败！";
			}
		}
		
		
		
		$detail = $act->gerActFromId( $id);
		if( $detail["faburen"] == $userid && $detail["shenhe"] != 2 ){//未审核通过 且是自己发布的
			//print_r($detail);
			$this->getView()->typeList = $act->getTypeList();
			$this->getView()->detail = $detail;
			$this->getView()->display("modify.html");
		}else{
			$this->getApp()->error404();
		}
		
	}
	/**
	 *  赞
	 * Enter description here ...
	 */
	public function Like(){
		$id = $this->getRequest()->get("id");
		$userinfo = $this->getData("userinfo");
		$userid = $userinfo["id"];
		$act = new activity();
		$code = $act->likeAct($userid, $id);
		//echo $code;
		if($code > 1){
			$this->getView()->setState('1');
			$this->getView()->setMsg("succeed!");
			$this->getView()->setData($code);
		}else{
			$this->getView()->setState("0");
			if($code==0){
				$this->getView()->setMsg("失败！");
			}else if( $code == -1 ){
				$this->getView()->setMsg("已赞过！");
			}else{
				$this->getView()->setMsg("不是进行中的活动！");
			}
			
		}
		$this->getView()->display("json");
		
	}
	
	public function Detail(){
		if( $userinfo = $this->getData("userinfo") ){
			$userid = $userinfo["id"];
		}else{
			$userid = -1;
		}
		//print_r($userinfo);
		$this->getView()->web_action = "all";//全部
		$id = intval($this->getRequest()->get("id"));
		$comment = new Comment();
		if($_POST && $userid > 0){
			//print_r($_POST);
			$content = $this->getRequest()->get("content");
			$touserid = intval( $this->getRequest()->get("comment_pid") );
			$comment->addCommon($id, $content, $userid ,$touserid);
		}
		$commentList = $comment->getAllComment($id);
		//print_r($commentList);
		$this->getView()->commentList = $commentList;
		
		
		$act = new activity();
		
		$detail = $act->gerActFromId($id,2,$userid);
		//print_r($detail);
		$pic_list = $act->getactpic($id);
		$this->getView()->pic_list = $pic_list;
		$this->getView()->detail = $detail;
		$this->getView()->display("detail.html");
	}
	
	public function Support(){
		if( $userinfo = $this->getData("userinfo") ){
			$userid = $userinfo["id"];
		}else{
			$this->gotoUrl("index","index");
			exit();
		}
		$this->getView()->web_action = "support";
		
		$state = $this->getRequest()->get("state") ? $this->getRequest()->get("state") : "all";
		$page = $this->getRequest()->get("page")? $this->getRequest()->get("page") : 1 ;
		$this->getView()->state = $state;
		if($state == "complete"){//完成的
			$state = 2;
		}else if($state == "ongoing"){//进行中的
			$state = 1;
		}else if($state == "failure"){//进行中的
			$state = 3;
		}else{//所有的
			$state = 0;
		}
		
		$act = new activity();
		$this->getView()->supportCount = $act->getSupportCount($userid);
		$this->getView()->myactCount = $act->getMyactCount($userid);
		$this->getView()->actList = $act->getMySupport($userid,$state,$page,10);
		//print_r($this->getView()->actList);
		
		
		$this->getView()->display("support.html");
	}
	
	public function Myact(){
		if( $userinfo = $this->getData("userinfo") ){
			$userid = $userinfo["id"];
		}else{
			$this->gotoUrl("index","index");
			exit();
		}
		$this->getView()->web_action = "myact";
		
		$state = $this->getRequest()->get("state") ? $this->getRequest()->get("state") : "all";
		$page = $this->getRequest()->get("page")? $this->getRequest()->get("page") : 1 ;
		$this->getView()->state = $state;
		if($state == "complete"){//完成的
			$state = 2;
		}else if($state == "ongoing"){//进行中的
			$state = 1;
		}else{//所有的
			$state = 0;
		}
		
		$act = new activity();
		$this->getView()->supportCount = $act->getSupportCount($userid);
		$this->getView()->myactCount = $act->getMyactCount($userid);
		$this->getView()->actList = $act->getMyAct($userid,$state,$page,10);
		//print_r($this->getView()->actList);
		
		
		$this->getView()->display("myact.html");
	}
	
	public function Addganyan(){
		if( $userinfo = $this->getData("userinfo") ){
			$userid = $userinfo["id"];
		}else{
			exit("禁止访问，请登录!");
		}
		$id = intval($this->getRequest()->get("id"));
		$act = new activity();
		
		if($_POST){
			//print_r($_POST);
			$img_list = array_key_exists("imglist", $_POST) ? $_POST["imglist"] : null;
			$act->add_act_pic($id,$img_list);
			$act->add_act_ganyan($id, $_POST["content"]);
			$this->gotoUrl('act','success');
			exit();
		}
		
		
		$detail = $act->gerActFromId($id,2,$userid);
		$pic_list = $act->getactpic($id);
		//print_r($pic_list);
		$this->getView()->pic_list = $pic_list;
		//print_r($detail);
		$this->getView()->detail = $detail;
		
		
		$this->getView()->web_action = "myact";
		$this->getView()->display("addganyan.html");
	}
	
	
}	