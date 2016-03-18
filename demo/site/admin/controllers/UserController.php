<?php
/**
*  
*  Create On 2013-8-7����11:35:13
*  Author lidianbin
*  QQ: 281443751
*  Email: lidianbin@iwind-tech.com
**/
class UserController extends Controller{

	public function __construct(){
		parent::__construct();
		$this->view->web_host=$this->getRequest()->hostUrl;
		$this->view->web_app_url=$this->getRequest()->hostUrl."/zcmanage.php";
	}

	
	public function Userlist(){
		$userinfo = $this->getData("userinfo");
		$user = new admin();
		if($do = $this->getRequest()->get("do") ){
			//echo $do;
			if($do = "del"){
				if($id = $this->getRequest()->get("id")){
					$result = $user->delAdmin($id);
					if($result){
						$msg = $this->_lang->yonghushanchuchenggong;
					}else{
						$msg = $this->_lang->yonghushanchushibai;
					}
					$this->view->result = $msg;
					$log = new log();
					$log->addLog($this->getRequest()->cName, $this->getRequest()->aName, $userinfo["admin_id"], $msg , "do:del,id:".$this->getRequest()->get("id") );
				}else{
					exit("access deny!");
				}
			}
		}
		$userList = $user->getAdminList();
		//print_r($userList);
		
		$this->view->userlist = $userList;
		echo $this->view->render("userlist.htm");
	}
	
	/**
	 * 角色列表
	 */
	public function Rolelist(){
		
		$role = new role();
		if($do = $this->getRequest()->get("do") ){
			if($do == "del"){
				if( $id = $this->getRequest()->get("id") ){
					
					if($role->delRole($id)){
						
						$log = new log();
						$userinfo = $this->getData("userinfo");
						$log->addLog($this->getRequest()->cName, $this->getRequest()->aName, $userinfo["admin_id"], $this->_lang->shanchujuese."【".$this->getRequest()->get("id")."】"  , "do:del,id:".$this->getRequest()->get("id") );
						
						$this->view->result = $this->_lang->shanchuchenggong;
					}else{
						$this->view->result = $this->_lang->shanchushibai;
					}
					
				}else{
					exit("access deny");
				}
			}
		}
		
		$roleList = $role->getRolesList();
		
		if($roleList){
			for($i=0; $i < count($roleList) ; $i++ ){
				$id = $roleList[$i]["role_id"];
				$roleList[$i]['res'] = $role->getResourceOfRole($id);	
			}
		}
		//print_r($roleList);
		$this->view->rolelist = $roleList;
		echo $this->view->render("rolelist.htm");
	}
	
	/**
	 * 添加角色
	 */
	public function Addrole(){
		$role = new role();
		if($_POST){
			//Array ( [rolename] => 水电费 [res] => Array ( [0] => 1 [1] => 2 ) [Submit] => 提交 )
			//print_r($_POST);
			
			$id = $role->addRole( $this->getRequest()->get('rolename') );
			
			if( $id >0 ){
				foreach ($_POST['res'] as $resId){
					$role->addResourceToRole($id, $resId);
				}
				$log = new log();
				$userinfo = $this->getData("userinfo");
				$log->addLog($this->getRequest()->cName, $this->getRequest()->aName,$userinfo["admin_id"],$this->_lang->tianjiajuese."【".$this->getRequest()->get("rolename")."】");
				$this->view->result = $this->_lang->tianjiachenggong;
			}else if( $id == -1 ){
				$this->view->result = $this->_lang->juesemingyicunzai;
			}else{
				$this->view->result = $this->_lang->tianjiashibai;
			}
		}
		$res = $role->getResourceList();
		//print_r($res);
		$this->view->resList = $res;
		echo $this->view->render("addrole.htm");
	}
	
	/**
	 * 修改角色
	 */
	public function Modifyrole(){
		
		if( $id = $this->getRequest()->get("id") ){
			$role = new role();
			
			if($_POST){
				
				if($rolename = $this->getRequest()->get("rolename") ){
					$result = $role->changeRolename($id,$rolename);
					if($result == 1){
						$role ->delResourceToRole($id);
						if($res = $this->getRequest()->get("res")){
							foreach ($res as $resId){
								$role->addResourceToRole($id, $resId);
							}
						}
						
						$log = new log();
						$userinfo = $this->getData("userinfo");
						$log->addLog($this->getRequest()->cName, $this->getRequest()->aName,$userinfo["admin_id"],$this->_lang->xiugaijuese."【".$rolename."】");
							
						
						$this->view->result = $this->_lang->xiugaichenggong;
						
					}else if($result == -1){
						$this->view->result = $this->_lang->juesemingyicunzai;
					}else{
						$this->view->result = $this->_lang->xiugaishibai;
					}
				
				}else{
					$this->view->result = $this->_lang->juesemingbunengweikong;
				}
			}
			
			
			
			$roleInfo = $role->getRoleFromRoleId($id);
			if($roleInfo){
				
				$id = $roleInfo["role_id"];
				$roleInfo['res'] = $role->getResourceOfRole($id);
				//print_r($roleInfo['res']);
				$resarr=array();
				if($roleInfo['res']){
					foreach ($roleInfo['res'] as $resitem){
						$resarr[] = $resitem['ctrl_id'];
					}
				}
				$reslist = $role->getResourceList();
				
				foreach($reslist as &$item){
					if( in_array( $item['ctrl_id'], $resarr ) ){
						$item['selected'] = 1;
					}else{
						$item['selected'] = 0;
					}
				}
				
				//print_r($res);
				$this->view->rolename = $roleInfo["role_name"];
				$this->view->resList = $reslist;
				
				echo $this->view->render("modifyrole.htm");
			}
		}else{
			
			$this->Rolelist();
		}
		
		
		
	}
	
	/**
	 * 添加用户
	 */
	public function Adduser(){
		if($_POST){
			//Array ( [user] => dsfd [pw] => fdsfsd [repw] => dfsfds [role] => 1 [Submit] => 提交 )
			//print_r($_POST);
			$pw = $this->getRequest()->get("pw");
			$repw = $this->getRequest()->get("repw");
			$realname = $this->getRequest()->get("realname");
			$email = trim($this->getRequest()->get("email"));
			$phone = trim($this->getRequest()->get("phone"));
			$roleid = $this->getRequest()->get("role");
			
			if($pw != $repw){
				$this->view->email = $email;
				$this->view->phone = $phone;
				$this->view->realname = $realname;
				$this->view->role = $roleid;
				$this->view->result = $this->_lang->liangcimimabuyizhi;
				
			}else{
				$user = new admin();	
				if( ! $email && !$phone ){
					$this->view->email = $email;
					$this->view->phone = $phone;
					$this->view->realname = $realname;
					$this->view->role = $roleid;
					$this->view->result = $this->_lang->shoujihaoheyouxiangbunengtongshiweikong;
				}else{
					$emailflag = 1;
					$phoneflag = 1; 
					if($email){
						$emailState = $user->adminCanUse($email);
						if($emailState>0){//可用
							
						}else if ($emailState == -1){//已存在
							$emailflag = 0;
							$this->view->email = $email;
							$this->view->phone = $phone;
							$this->view->realname = $realname;
							$this->view->role = $roleid;
							$this->view->result = $this->_lang->youxiangyibeizhanyong;
						}else{//不可用
							$emailflag = 0;
							$this->view->email = $email;
							$this->view->phone = $phone;
							$this->view->realname = $realname;
							$this->view->role = $roleid;
							$this->view->result = $this->_lang->youxiangbukeyong;
						}
					}
					
					if($emailflag && $phone){
						$phoneState = $user->adminCanUse($phone);
						if($phoneState>0){//可用
								
						}else if ($phoneState == -1){//已存在
							$phoneflag = 0;
							$this->view->email = $email;
							$this->view->phone = $phone;
							$this->view->realname = $realname;
							$this->view->role = $roleid;
							$this->view->result = $this->_lang->shoujihaoyibeizhanyong;
						}else{//不可用
							$phoneflag = 0;
							$this->view->email = $email;
							$this->view->phone = $phone;
							$this->view->realname = $realname;
							$this->view->role = $roleid;
							$this->view->result = $this->_lang->shoujihaobukeyong;
						}
					}
					
					if( $emailflag && $phoneflag ){
						$userinfo = $this->getData("userinfo");
						$result = $user->addAdmin($email,$phone, $realname, $pw, $roleid, $userinfo["admin_id"] );
						
						if($result>0){
							$log = new log();
							$log->addLog($this->getRequest()->cName, $this->getRequest()->aName,$userinfo["admin_id"],$this->_lang->tianjiayonghu.":".$realname."【".$result."】");
							$this->view->result = $this->_lang->gongxinin."[".$_POST['realname']."]".$this->_lang->tianjiachenggong;
						}else{
							$this->view->email = $email;
							$this->view->phone = $phone;
							$this->view->realname = $realname;
							$this->view->role = $roleid;
							$this->view->result = $this->_lang->tianjiashibai;
						}
						
					}
				
				}
			
			}
			
		}
		$role = new role();
		$rolelist = $role->getBackRole();
		$this->view->role = $rolelist;
		echo $this->view->render("adduser.htm");
	}
	
	public function Modifyuser(){
		
		if( $id = $this->getRequest()->get("id") ){
			$user = new admin();
			if( $user_info = $user->getAdminFromAdminid($id) ){
				if($user_info["create_admin_id"] == 0){
					exit("access deny!");
				}
			}else{
				$this->gotoUrl("user","userlist");
				exit();
			}
			if($_POST){
				$changePw = true;
				//print_r($_POST);
				if( $_POST['pw'] != "" && $_POST['pw'] == $_POST['repw'] ){
					//echo $_POST['pw'];
					$changePw = $user->setPw($id, $_POST['pw']);
				}
				
				if($changePw){

					$email = trim($this->getRequest()->get("email"));
					$phone = trim($this->getRequest()->get("phone"));
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
						
						if($emailflag && $phoneflag ){
							$realname = $this->getRequest()->get("realname");
							$role = $this->getRequest()->get("role");
							$result = $user->modAdminInfo( $id, array("admin_realname"=>$realname,"role_id"=>$role ,"admin_phone" => $phone,"admin_mail"=>$email));
							if($result){
								
								$log = new log();
								$userinfo = $this->getData("userinfo");
								$log->addLog($this->getRequest()->cName, $this->getRequest()->aName,$userinfo["admin_id"],$this->_lang->xiugaiyonghu.":".$realname."【".$id."】");
								
								$this->view->result = $this->_lang->xiugaichenggong;
							
							}else{
								$this->view->result = $this->_lang->xiugaibufenshibai;
							}
						}
					}
			
				}else{
					$this->view->result = $this->_lang->xiugaishibai;
				}
					
			}
			
			$userinfo = $user->getAdminFromAdminid($id);
			if($userinfo){
				$this->view->userInfo = $userinfo;
			}
			$role = new role();
			$rolelist = $role->getRolesList();
			$this->view->role = $rolelist;
			echo $this->view->render("modifyuser.htm");
		}else{
			$this->Userlist();
		}
		
		
	}

}
