<?php
include_once "Filter.class.php";
class indexFilter extends Filter{
		
	//属于账号的资源,不登陆不能访问
	protected $_selfRS = array(	'Account' => array('Myinfo'),
								'Act' => array('Add','Like','Modify')
							);
	
	
	
	public function doFilter(){
		
		$session = $this->getApp()->loadUtilClass("SessionUtil");
		$this->getApp()->getView()->setStatus("0");
		
		if($userid = $session->get("session_index_userid") ){
			//echo $userid;
			$user = new user();
			if( $userinfo  = $user->getUserFromUserId($userid) ){
				$this->getApp()->getView()->setStatus("1");
				$this->getApp()->putData('userinfo', $userinfo );
				$this->getApp()->getView()->__USERINFO__ = $userinfo;
			
			}else{
				$session->clear();
				if($this->getRequest()->get("method") == "ajax"){
					$this->getApp()->getView()->setState("0");
					$this->getApp()->getView()->setMsg("登陆信息有误！");
					$this->getApp()->getView()->display("json");
				}else{
					echo "登陆信息有误！";
				}
				exit();
				
			}
		}else{
			if($this->canViste($this->getCName(), $this->getAName()) ){
				
			}else{
				if($this->getRequest()->get("method") == "ajax"){
					$this->getApp()->getView()->setState("0");
					$this->getApp()->getView()->setMsg("请登陆！");
					$this->getApp()->getView()->display("json");
				}else{
					echo "请登陆！";
				}
				exit();
			}
		}
	
	}
	
	//检查是否可以访问
	public function canViste($cName,$aName){
		if( array_key_exists($cName,$this->_selfRS) ? in_array( $aName, $this->_selfRS[$cName]  ) : false ){
			return false;
		}
		return true;
	}
	
}

?>