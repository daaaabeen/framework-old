<?php
/**
*  Create On 2013-7-25
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/
class AccountController extends Controller{
	
	public function __construct(){
		//print_r($_COOKIE);
		parent::__construct();
		$this->view->web_host = $this->getRequest()->hostUrl;
		$this->view->web_app_url = $this->getRequest()->hostUrl."/zcmanage.php";
	}
	
	public function Index(){
		$this->login();
	}
	
	public function Login(){	
		if($_POST){
			$log = new log();
			$user=new admin();
			$loginre=$user->authAdmin($_POST['username'],$_POST['password'],0);
			//print_r($loginre);
			//exit();
			if($loginre['result'] > 0){
				//$log = new Log();
				//$log->addAdminLoginLog($loginre['result'], $_SERVER["REMOTE_ADDR"]);
				$log->addLog($this->getRequest()->cName, $this->getRequest()->aName,$loginre['result'],$this->_lang->dengluchenggong,$_POST["username"]);
				$session = $this->getApp()->loadUtilClass("SessionUtil");
				//print_r($this->getApp());
				$session->set( "session_userid", $loginre["result"] );
				$this->gotoUrl("index","index",'3');
				exit();
			}elseif($loginre['result']== -1){
				$mess=$this->_lang->$loginre['msg'];
			}elseif($loginre['result']== -2){
				$mess=$this->_lang->$loginre['msg'];
			}else{
				$mess=$this->_lang->dengluchucuole;
				
			}
			
			$log->addLog($this->getRequest()->cName, $this->getRequest()->aName,0,$mess,$_POST["username"]);
			
			$this->view->message=$mess;
			$this->view->username=$_POST['username'];
		}else{
			$session = $this->getApp()->loadUtilClass("SessionUtil");
			if( $session->get( "session_userid") ){
				$this->gotoUrl("index","index","5");
				exit("跳转");
			}
		
		}
		echo $this->view->render("login.htm");
	
	}//-----------------------------------------
	
	public function Logout(){
		$session = $this->getApp()->loadUtilClass("SessionUtil");
		$session->clear();
		$this->gotoUrl("account","login");
	}
	
	public function Changepw(){
		if($_POST){
			if($_POST['old'] && $_POST['new'] && $_POST['renew']){
				if($_POST['new'] != $_POST['renew']) $result = $this->_lang->liangcimimabuyizhi;
				else{
					$user = new admin();
					$userinfo = $this->getData('userinfo');
					$reCode = $user -> changePw( $userinfo['admin_id'], $_POST['old'], $_POST['new'] ); //修改密码	
					if( $reCode == 1){
						
						$result = $this->_lang->xiugaichenggong;
					}else if( $reCode == -1 ){
						$result = $this->_lang->mimashurucuowu;
					}else if( $reCode == -2 ){
						$result = $this->_lang->yonghuyichang;
					}else {
						$result = $this->_lang->xiugaishibai;
					}
				}
			}else{
				$result = $this->_lang->xinxishurubuwanzheng;
			}
		}else{
			$result="";
		}
		
		$this->view->result=$result;
		echo $this->view->render("changepw.htm");
	}
	
	public function Myinfo(){
		//Array ( [user_id] => 1 [user_name] => admin [user_realname] => ewwewe [user_pw] => ccad7b1ca9998882f9188310e67cdccb 
		//[user_salt] => 74cb7abedafeded8 [role_id] => 1 [user_regtime] => 2012-02-02 00:00:00 [role_name] => 超级管理员 )
		$userinfo = $this->getData("userinfo");
		if($_POST){
			$id = $userinfo["admin_id"];
			$user = new admin();
			$realname = trim($_POST["realname"]);
			$phone = trim( $_POST["phone"]);
			$email = trim($_POST["email"]);
			
			$userinfo["admin_realname"] = $realname;
			$userinfo["admin_phone"] = $phone;
			$userinfo["admin_mail"] = $email;
			if( ! $email && !$phone ){
				//echo "手机号和邮箱不能同时为空";
				$this->view->result = $this->_lang->shoujihaoheyouxiangbunengtongshiweikong;
			}else {
				$emailflag = 1;
				$phoneflag = 1;
				if( $email ){
					if( $user->isEmail($email) ){
						
						if( $existsuser = $user->getAdminFromEmail( $email )){
							if($existsuser["admin_id"] != $id){
								$this->view->result = $this->_lang->emailyicunzai;
								$emailflag=0;
							}
						}
						
					}else{
						$this->view->result = $this->_lang->emailgeshibuzhengque;
						//echo "email 格式不正确！";
						$emailflag=0;
					}
				}
				
				if( $phone ){
					if( $user->isPhone($phone) ){
						if( $existsuser = $user->getAdminFromPhone( $phone )){
							if($existsuser["admin_id"] != $id){
								$this->view->result = $this->_lang->emailyicunzai;
								//echo "email 格式不正确！";
								$emailflag=0;
							}
						}
					}else{
						$this->view->result = $this->_lang->shoujihaomageshibuzhengque;
						//echo "phone 格式不正确！";
						$phoneflag = 0;
					}
				}
				
				if( $emailflag && $phoneflag ){
					$result = $user->modAdminInfo($id, array("admin_realname"=>$realname,"admin_phone"=>$phone,"admin_mail"=>$email ));
					if($result){
						$this->view->result = $this->_lang->xiugaichenggong;
					}else{
						$this->view->result = $this->_lang->xiugaishibai;
					}
				}
				
			}
			
			
		}
		
		$log =  new log();
		$page = $this->getRequest()->get("page") ? $this->getRequest()->get("page") : 1 ;
		$loglist = $log->getUserLoginLogPageModel( $userinfo["admin_id"] ,$page );
		//print_r($loglist);
		$this->view->logList = $loglist;
		
		$this->view->userInfo = $userinfo;
		
		echo $this->view->render("myinfo.htm");
	}
}