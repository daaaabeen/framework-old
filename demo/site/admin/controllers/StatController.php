<?php
/**
*  
*  Create On 2013-7-29 4:51:22
*  Author lidianbin
*  QQ: 281443751
*  Email: lidianbin@iwind-tech.com
**/
class StatController extends Controller{

	public function __construct(){
		parent::__construct();
		$this->view->web_host = $this->getRequest()->hostUrl;
		$this->view->web_app_url = $this->getRequest()->hostUrl."/zcmanage.php";
	}


	/**
	 * 系统日志
	 */
	public function Loglist(){
		$user = new admin();
		$log = new log();
		if($_POST){
			$userid = $_POST["user"];
			$start = $_POST["start"];
			$end = $_POST["end"];
			$page = 1 ;
			//$logList = $log->getUserLoginLogPageModel($userid,$page,10,$start,$end);
			if($userid > 0){
				$userinfo = $user->getAdminFromAdminid($userid);
				$username = $userinfo["admin_realname"];
			}else{
				$username = "不限";
			}
			$this->view->result = "用户：".$username."&nbsp;时间段：".($start ? $start:"不限" )."&nbsp;——&nbsp;".($end ? $end:"不限");
				
		}else{
			$userid = $this->getRequest()->get("userid") ;
			$start = $this->getRequest()->get("start") ?  $this->getRequest()->get("start") : 0 ;
			$end = $this->getRequest()->get("end")?  $this->getRequest()->get("end") : 0 ;
			$page = $this->getRequest()->get("page") ? $this->getRequest()->get("page") : 1 ;
			
			if($userid > 0){
				$userinfo = $user->getAdminFromAdminid($userid);
				$username = $userinfo["admin_realname"];
			}else{
				$username = "不限";
			}
			$this->view->result = "用户：".$username."&nbsp;时间段：".($start ? $start:"不限" )."-".($end ? $end:"不限");
		}
		
		$this->view->userid = $userid;
		$this->view->start = $start;
		$this->view->end = $end;
		$this->view->userList = $user->getAdminList();
		
		$logList = $log->getLogPageModel($userid,$page,10,$start,$end);
		//print_r($logList);
		
		
		$this->view->logList = $logList;
		echo $this->view->render("loglist.htm");
	}
	
	
	/*
	 *用户反馈
	*/
	public function feedback(){
		$pagesize = 10;
		$feedback = new feedback();
		if($_POST){
			$platform = $_POST["platform"];
			$start = $_POST["start"];
			$end = $_POST["end"];
			$page = 1 ;
			$avglist = $feedback->getFeedbackAvgScore($platform,$start,$end);
			$feedbackList = $feedback->getFeedbackPageModel($platform,$page,$pagesize,$start,$end);
				
			if($platform == 1){
				$platformName = "web";
			}else if($platform == 2){
				$platformName = "android";
			}else if($platform == 3){
				$platformName = "ios";
			}else{
				$platformName = "不限";
			}
			$this->view->result = "平台：".$platformName."&nbsp;时间段：".($start ? $start:"不限" )."&nbsp;——&nbsp;".($end ? $end:"不限");
	
		}else{
			$platform = $this->getRequest()->get("platform") ;
			$start = $this->getRequest()->get("start") ?  $this->getRequest()->get("start") : 0 ;
			$end = $this->getRequest()->get("end")?  $this->getRequest()->get("end") : 0 ;
	
			$page = $this->getRequest()->get("page") ? $this->getRequest()->get("page") : 1 ;
				
			$avglist = $feedback->getFeedbackAvgScore($platform,$start,$end);
			$feedbackList = $feedback->getFeedbackPageModel($platform,$page,$pagesize,$start,$end);
			if($platform == 1){
				$platformName = "web";
			}else if($platform == 2){
				$platformName = "android";
			}else if($platform == 3){
				$platformName = "ios";
			}else{
				$platformName = "不限";
			}
			$this->view->result = "平台：".$platformName."&nbsp;时间段：".($start ? $start:"不限" )."&nbsp;——&nbsp;".($end ? $end:"不限");
	
		}
		$this->view->avg =$avglist;
		$this->view->platform = $platform;
		$this->view->start = $start;
		$this->view->end = $end;
	
		//print_r($logList);
		$this->view->feedbackList = $feedbackList;
		$this->view->display("feedback.htm");
	}
	
	
	public function frontlog(){
		
		$log = new ulog();
		
		if($_POST){
			$usertype = $_POST["usertype"];
			$username = $_POST["username"] ? $_POST["username"] : "null" ;
			$start = $_POST["start"];
			$end = $_POST["end"];
			$page = 1 ;
		}else{
			$page = $this->getRequest()->get("page") ? $this->getRequest()->get("page") : 1 ;
			$usertype = $this->getRequest()->get("usertype") ? $this->getRequest()->get("usertype") : 2;
			$username = urldecode($this->getRequest()->get("username"));
			$start = $this->getRequest()->get("start") ?  $this->getRequest()->get("start") : 0 ;
			$end = $this->getRequest()->get("end")?  $this->getRequest()->get("end") : 0 ;
		}		
		
		if($usertype == 1 ){
			$usertypename = "教师";
		}elseif($usertype == 0){
			$usertypename = "学生";
		}else{
			$usertypename = "不限";
		}
		if($username == "null"){
			$usernickname = "不限";
		}
		else{
			$usernickname = $username;
		}
		$this->view->result = "用户类型：".$usertypename."&nbsp;用户姓名：".$usernickname."&nbsp;时间段：".($start ? $start:"不限" )."-".($end ? $end:"不限");
		$this->view->usertype = $usertype;
		$this->view->username = $username;
		$this->view->start = $start;
		$this->view->end = $end;
		
		$logList = $log->getulog($page, 10, $usertype, $username, $start, $end);
		$this->view->logList = $logList;
	
		echo $this->view->render("frontlog.html");
	}
	

	
}

?>