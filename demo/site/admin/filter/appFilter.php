<?php
include_once "Filter.class.php";
class appFilter extends Filter{
	
	protected $_openRS = array(
			'Index' => array('Test'),
			'Account' => array('Login',''),
			'Upload' =>array('Upload'),
			'Word' =>array('Editword','Readword')
	); //鍏佽涓嶇敤鐧婚檰璁块棶鐨勮祫婧�
	
	//灞炰簬璐﹀彿鐨勮祫婧�
	protected $_selfRS = array(	'Account' => array('Index','Login','Logout','Changepw','Myinfo'),
								'Index'	=>	array('Index','Main','Menu','Header') );
	
	//鏈�粓鐨勮祫婧愬垪琛�
	protected $_RSList = array();
	
	public function doFilter(){
		
		$session = $this->getApp()->loadUtilClass("SessionUtil");
		
		if($userid = $session->get("session_userid") ){
		//if($userid=1){//娴嬭瘯鐢�
			//print_r($_SESSION);
			$user=new admin();
			//print_r($_COOKIE);
			//echo $userid."<br/>";
			$userdata=$user->getAdminFromAdminid($userid);
			//print_r($userdata);
			//exit();
			if($userdata){
				//$this->getApp()->getView()->setStatus(1);
				//璁剧疆鐢ㄦ埛鐨勪俊鎭�
				$this->getApp()->putData('userinfo', $userdata );
				
				
				$this->_RSList = array_merge( $this->_RSList,$this->_selfRS );
				
				$role = new role();
				if( $resList = $role->getResourceOfRole($userdata["role_id"]) ){
					
					foreach ($resList as $res){
						$this->_RSList[ $res["ctrl_class"] ] = array();	
						$ctrl_id = $res["ctrl_id"];
						if($action_list = $role->getActionList($ctrl_id)){
							foreach ($action_list as $al){
								$this->_RSList[ $res["ctrl_class"] ][] = $al["action_function"];
							}
						}
						 	
					}
					
				}
				
				
				if($this->canViste($this->getCName(), $this->getAName())){
					
					$this->getApp()->putData('resource', $this->_RSList );
				
				}else{
					echo "access deny!";
					exit();
				}
				
				
	
			}else{
				$session->clear();
				echo "session error, access deny!";
				exit();
			}
		}else{
			$view = $this->getApp()->getView();
			//echo $this->getCName().$this->getAName();
			if( $this->canViste( $this->getCName(), $this->getAName() ) ){
				//$this->getApp()->gotoUrl("Account","login");
				//echo "no login but can access!<br/>";
				//$view->setStatus("0");
			}else{
				
				$this->getApp()->gotoUrl("Account","login");
				exit("no login access deny!");
			
			}
		}
	
	}
	
	//妫�煡鏄惁鍙互璁块棶
	public function canViste($cName,$aName){
		if( $this->isOpenRS($cName, $aName) ){
			return true;
		}else{
			return array_key_exists($cName,$this->_RSList) ? in_array( $aName, $this->_RSList[$cName]  ) : false;
		}
	}
	
	public function isOpenRS($cName,$aName){
		return  array_key_exists($cName, $this->_openRS) ? in_array($aName, $this->_openRS[$cName]) :false ;
	}

}

?>