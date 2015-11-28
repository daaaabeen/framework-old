<?php
/**
*  Create On 2010-8-21
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/
class admin extends Model
{


	/**
	 * 根据用户的邮箱获取用户信息
	 * return false / array()
	 */
	public function getAdminFromEmail( $val ){

		$sql = "select `admin`.*,`role`.* from `admin`
				LEFT JOIN `role` ON `admin`.`role_id` = `role`.`role_id`
				where `admin`.`admin_mail` = '".$val."' ";
		return $this->fetchRow($sql);

	}


	/**
	 * 根据用户手机号获取用户信息
	 * return false / array()
	 */
	public function getAdminFromPhone( $val ){

		$sql = "select `admin`.*,`role`.* from `admin`
				LEFT JOIN `role` ON `admin`.`role_id` = `role`.`role_id`
				where `admin`.`admin_phone` = '".$val."' ";
		//echo $sql;
		return $this->fetchRow($sql);

	}

	/**
	 * 跟聚用户的邮箱或手机号获取用户信息
	 * @param unknown_type $value
	 *
	 */
	public function getAdminFromEmailOrPhone($val){
		//echo $val;
		if($this->isEmail($val)){
			//echo "email";
			return $this->getAdminFromEmail($val);
		}
		if($this->isPhone($val)){
			//echo "phone";
			return $this->getAdminFromPhone($val);
		}
		else{
			return false;
		}
	}

	public function isEmail($val){
		$reg='/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/';

		return preg_match($reg, $val);
	}

	public function isPhone($val){
		return preg_match('/^1[3458][0-9]{9}$/',$val);
	}

	/**
	 * 根据用户id获取用户信息
	 * @param int $userid
	 * @return Ambigous <boolean, multitype:>
	 */
	public function getAdminFromAdminid($userid){

		$sql = "select `admin`.*,`role`.* from `admin`
				LEFT JOIN `role` ON `admin`.`role_id` = `role`.`role_id`
				where `admin`.`admin_id` = '".$userid."' ";
		//echo $sql;
		return $this->fetchRow($sql);
	}


	/**
	 * 验证用户名密码
	 * @param unknown_type $username
	 * @param unknown_type $password
	 * @param int $isid 1 id ， 0 邮箱或者手机号
	 * @return array( 'result', 'userinfo', 'msg') | boolean
	 * result:成功：id  密码错误：-1  用户不存在：-2
	 * userinfo：用户信息
	 * msg：描述
	 */
	public function authAdmin($user,$password,$isid=1){
		$result = array();
		if($isid){
			$userinfo = $this->getAdminFromAdminid($user);

		}else{
			$userinfo = $this->getAdminFromEmailOrPhone($user);
		}

		if($userinfo){
			//echo $this->generatePw($password, $userinfo['user_salt']);
			if($userinfo['admin_pw'] == $this->generatePw($password, $userinfo['admin_salt'] ) ){
				$result['result'] = $userinfo['admin_id'];
				$result['userinfo'] = $userinfo;
				$result['msg'] = "yangzhengchenggong";

			}else{
				$result['result'] = -1;
				$result['userinfo'] = null;
				$result['msg'] = "mimacuowu";
			}

		}else{
			$result['result'] = -2;
			$result['userinfo'] = null;
			$result['msg'] = "yonghubucunzai";
		}
		return $result;
	}

	/**
	 * 添加用户的手机设备信息
	 * @param unknown_type $userid
	 * @param unknown_type $osType
	 * @param unknown_type $token
	 * @return boolean|resource
	 */
	public function setPhoneInfo($userid,$osType,$token){
		if( strtolower($osType) == "android" ){
			$osid= 1;
		}else if( strtolower($osType) == "ios" ){
			$osid= 2;
		}else{
			return false;
		}
		$sql = "SELECT * FROM  `user`  WHERE  `code_platform` =".$osid." AND `token` = '".$token."' " ;
		if( $result = $this->fetchRow($sql) ){

			if($result["user_id"] != $userid){

				$this->delPhoneInfo($result["user_id"]);

				$sql = "UPDATE `user` SET `code_platform` = '".$osid."',`token` = '".$token."' WHERE `user`.`user_id` =".$userid." ";
				return $this->update($sql);
			}

		}else{
			$sql = "UPDATE `user` SET `code_platform` = '".$osid."',`token` = '".$token."' WHERE `user`.`user_id` =".$userid." ";
			return $this->update($sql);
		}
	}

	/**
	 * 删除账号登录的手机信息
	 * @param unknown_type $userid
	 * @return resource
	 */
	public function delPhoneInfo($userid){
		$sql = "UPDATE `user` SET `code_platform` = '0',`token` = NULL WHERE `user`.`user_id` =".$userid." ";
		return $this->update($sql);
	}

	/**
	 * 添加用户
	 * @return 插入失败：false    注册成功：用户id
	 */
	public function addAdmin($email,$phone,$realname,$pw,$roleid,$userid){
		$salt = $this->_getSalt();
		$pw = $this->generatePw($pw, $salt);
		$sql =" INSERT INTO `howdo`.`Admin` (`admin_id`, `admin_realname`, `admin_pw`, `admin_salt`, `role_id`, `admin_create_time`, `admin_phone`, `admin_mail`, `create_admin_id`) 
				VALUES 
				(NULL, '".$realname."', '".$pw."', '".$salt."', '".$roleid."', NOW(), '".$phone."', '".$email."', '".$userid."')";
		//echo $sql;
		return $this->insert($sql);
	}

	/**
	 * 查看用户名是否可用
	 * @param unknown_type $username
	 * @return -2 非法字符  -1已存在  1可用
	 */
	public function adminCanUse($username){

		if($this->haveBadTag($username)){
			return -2;
		}
		if( $this->isEmail($username) ){
			return $this->getAdminFromEmail($username) ? -1 : 1 ;
		}else if($this->isPhone($username)){
			return $this->getAdminFromPhone($username) ? -1 : 1 ;
		}else{
			return -2;
		}


	}

	/**
	 * 生成加密后的密码
	 * @param unknown_type $pw
	 * @param unknown_type $salt
	 * @return string
	 */
	public function generatePw($pw,$salt){
		return md5(md5($pw.$salt));
	}

	/**
	 * 获取一个随机生成的字符串
	 * @param unknown_type $username
	 */
	protected  function _getSalt($str=""){
		return substr(md5($str.time()),2,16);
	}




	/**
	 * 修改用户资料
	 * @param  $userId用户id ,$arr[key] = value
	 * @return bool
	 */
	public function modAdminInfo($userId,$arr){
		if($arr){
			reset($arr);
			list($key, $val) = each($arr);
			$str = " `".$key."` = '".$val."' ";
			while (list($key, $val) = each($arr)) {
				//echo "$key => $val\n";
				if($val){
					$str .= " , `".$key."` = '".$val."' ";
				}else{
					$str .= " , `".$key."` = NULL ";
				}
			}
			$sql = "UPDATE `admin` SET ".$str." WHERE `admin_id` =".$userId."  LIMIT 1" ;
			//echo $sql;
			return $this->update($sql);
		}else{
			return false;
		}

	}


	/**
	 * 设置密码
	 * @param unknown_type $uid
	 * @param unknown_type $pw
	 * @return string|boolean
	 */
	public function setPw($uid,$pw){
		$salt = $this->_getSalt($uid);
		$passWord = $this->generatePw($pw, $salt);
		if($this->modAdminInfo($uid, array( 'admin_pw' => $passWord,'admin_salt'=>$salt ))){
			return $passWord;
		}else{
			return false;
		}
	}
	
	/**
	 * 修改密码
	 * @param unknown_type $user_id
	 * @param unknown_type $old
	 * @param unknown_type $new
	 * @return 1成功， -1密码错误，  -2用户不存在，  -3未知错误 
	 */
	public function changePw( $user_id, $old, $new ){
		if( $result = $this->authAdmin($user_id, $old, 1 ) ){
			//print_r($result);
			if($result["result"] > 0){
				if( $this->setPw($user_id, $new) ){
					return 1;
				}else{
					return -3;
				}
			}else{
				return $result["result"];
			}
			
		}else{
			return -3;
		}
	}
	
	/**
	 * 获取管理员列表
	 * @param unknown_type $filter
	 * @param unknown_type $page
	 * @param unknown_type $num
	 * @return Ambigous <boolean, multitype:>
	 */
	public function getAdminList($filter=null, $page=1, $num=null){
		$sql =	"SELECT  `admin`. * ,  `admin1`.`admin_realname` AS  `f_name` ,  `role`. * 
				FROM  `admin` 
				LEFT JOIN  `admin` AS  `admin1` ON  `admin`.`create_admin_id` =  `admin1`.`admin_id` 
				LEFT JOIN  `role` ON  `admin`.`role_id` =  `role`.`role_id` ";
		$limit ="";
		if($filter){
			reset($filter);
			list($key, $val) = each($filter);
			$str = " WHERE `".$key."` = '".$val."' ";
			while (list($key, $val) = each($filter)) {
				//echo "$key => $val\n";
				$str .= " AND `".$key."` = '".$val."' ";
			}
			$sql.=$str;
		}
		
		if($num){
			$limit = " LIMIT ".($page-1)*$num." , ".$num;
		}
		$sql.=$limit;
		//echo $sql;
		return $this->fetchAll($sql);
	}
	
	/**
	 * 删除用户
	 * @param unknown_type $id
	 * @return resource
	 */
	public function delAdmin($userid){
		$sql = "DELETE FROM `admin` WHERE `admin_id` = ".$userid." AND `create_admin_id` != 0   LIMIT 1";
		return $this->del($sql);
	}


}