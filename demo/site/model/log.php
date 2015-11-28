<?php
//插入一条日志，获取用户登录记录（查看一条，查看多条），按时段查看网站浏览量
class log extends Model{
	
	private $__userlogin=1;
	private $__sitevisite=0;
	
	
	/**
	 * 选取用户上次的登录信息
	 * @param unknown_type $userid
	 * @return Ambigous <boolean, multitype:>
	 */
	public function getLastLoginLog($userid){
		$sql = "SELECT * FROM `log` WHERE `log`.`ctrl_name` = 'Account' AND `log`.`action_name` = 'Login' AND `admin_id`='".$userid."' ORDER BY `log_time` DESC limit 0,1 ";
		return $this->fetchRow($sql);
	}
	
	/**
	 * 获取用户登录的日志
	 * @param unknown_type $userid
	 * @param unknown_type $page
	 * @param unknown_type $num
	 * @param unknown_type $start
	 * @param unknown_type $end
	 * @return multitype:unknown number Ambigous <boolean, multitype:>
	 */
	public function getUserLoginLogPageModel($userid = 0,$page =1 , $num = 10 , $start = null,$end =null){
		$filter = " `log`.`ctrl_name` = 'Account' AND `log`.`action_name` = 'Login' "; 
		if($userid > 0){
			$filter .= " AND `log`.`admin_id` = ".$userid." ";
		}
		if($start){
			$filter .= " AND `log`.`log_time` > '".$start."' "; 
		}
		if($end){
			$filter .= " AND `log`.`log_time` < '".$end."' ";
		}
		$sql = "SELECT `log`.*,`admin`.* FROM `log` 
				LEFT JOIN `admin` ON `log`.`admin_id` = `admin`.`admin_id`  
				WHERE ".$filter." 
				ORDER BY  `log`.`log_time` DESC  
				Limit ".($page-1)*$num.",".$num." ";
		//echo $sql;
		$list = $this->fetchAll($sql);
		$total = $this->getTotal('log',$filter);
		$totalPage = ceil($total / $num);
		return array('page'=>$page,'list'=>$list,'total'=>$total,'totalPage'=>$totalPage);
	}
	
	/**
	 * 
	 * @param unknown_type $userid
	 * @param unknown_type $page
	 * @param unknown_type $num
	 * @param unknown_type $start
	 * @param unknown_type $end
	 * @return multitype:unknown number Ambigous <boolean, multitype:>
	 */
	public function getLogPageModel($userid = 0,$page =1 , $num = 10 , $start = null,$end =null){
		//$filter = " `log`.`ctrl_name` = 'Account' AND `log`.`action_name` = 'Login' ";
		$filter = "1";
		if($userid > 0){
			$filter .= " AND `log`.`admin_id` = ".$userid." ";
		}
		if($start){
			$filter .= " AND `log`.`log_time` > '".$start."' ";
		}
		if($end){
			$filter .= " AND `log`.`log_time` < '".$end."' ";
		}
		
		
		$sql = "SELECT `log`.*,`admin`.`admin_realname` FROM `log` 
				LEFT JOIN `admin` ON `log`.`admin_id` = `admin`.`admin_id` 
				WHERE ".$filter." 
				ORDER BY  `log`.`log_time` DESC 
				Limit ".($page-1)*$num.",".$num." ";
		//echo $sql;
		$list = $this->fetchAll($sql);
		$total = $this->getTotal('log',$filter);
		$totalPage = ceil($total / $num);
		return array('page'=>$page,'list'=>$list,'total'=>$total,'totalPage'=>$totalPage);
	}
	
	
	/**
	 * 添加一条日志
	 * @param unknown_type $userid
	 * @param unknown_type $logType
	 * @param unknown_type $logip
	 * @return Ambigous <boolean, number>
	 */
	public function addLog($ctrl,$action,$userid=0,$info="",$post_param=""){
		$sql = "INSERT INTO `log` (`log_id`, `admin_id`, `ctrl_name`, `action_name`, `post_param`, `log_time`, `log_ip`, `log_info`) VALUES (NULL, '".$userid."', '".$ctrl."', '".$action."', '".$post_param."', NOW(), '".$this->getIp()."', '".$info."') ";
		//echo $sql;
		return  $this->insert($sql);
	}
	
	/**
	 * 获取用户的ip地址
	 * @return unknown
	 */
	public function getIp(){
		//$log = new Log();
		return array_key_exists("HTTP_X_REAL_IP", $_SERVER) ? $_SERVER["HTTP_X_REAL_IP"] : $_SERVER["REMOTE_ADDR"];
	}
	
	
}

?>