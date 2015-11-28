<?php
class user extends Model {
	
	/**
	 * 注册用户
	 * Enter description here ...
	 */
	public function registerUser($userobj){
		if($userinfo = $this->getUserFromRRId($userobj->id)){
			if( $userinfo["user_name"] == $userobj->name && json_encode( $userinfo["user_avatar"]) == json_encode($userobj->avatar) ){
				return $userinfo;
			}else{
				$info = array( "user_name"=>$userobj->name, "user_avatar" => json_encode( $userobj->avatar ) );
				if( $this->ModifyUserInfo($userinfo["id"], $info ) ){
					$userinfo["user_name"] = $userobj->name;
					$userinfo["user_avatar"] = $userobj->avatar ;
					return $userinfo;
				}
			}
		}else{
			$sql = "INSERT INTO `nkzc`.`zc_user` (`id`, `user_name`, `user_create_time`, `renren_id`, `user_avatar`) 
					VALUES (NULL, '".$userobj->name."', NOW(), '".$userobj->id."', '".json_encode( $userobj->avatar )."')";
			if($id = $this->insert($sql)){
				return $this->getUserFromUserId($id);
			}else{
				return false;
			}
		}
		
	}
		
	/**
	 * 根据人人id获取用户信息
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function getUserFromRRId($id){
		$sql = "SELECT * FROM  `zc_user` WHERE  `renren_id` =  '".$id."' ";
		
		if($result = $this->fetchRow($sql)){
			$result["user_avatar"] = json_decode( $result["user_avatar"] );
			return $result;
		}else{
			return false;
		}
	}
	
	/**
	 * 根据用户id获取用户信息
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function getUserFromUserId($id){
		$sql = "SELECT * FROM  `zc_user` WHERE  `id` =  '".$id."' ";
		if($result = $this->fetchRow($sql)){
			$result["user_avatar"] = json_decode( $result["user_avatar"] );
			return $result;
		}else{
			return false;
		}
	}
	
	
	/**
	 * 修改用户信息
	 * Enter description here ...
	 * 
	 */
	public function ModifyUserInfo($userId, $info) {
		
		reset($info);
		list($key, $val) = each($info);
		$str = " `".$key."` = '".$val."' ";
		while (list($key, $val) = each($info)) {
			//echo "$key => $val\n";
			if($val){
				$str .= " , `".$key."` = '".$val."' ";
			}else{
				$str .= " , `".$key."` = NULL ";
			}
		}
		
		
		
		$sql = "UPDATE `zc_user` SET ".$str." WHERE `id` =".$userId."  LIMIT 1" ;
		//echo $sql;
		return $this->update($sql);
		
	}
	
	
}