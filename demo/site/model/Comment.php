<?php
class Comment extends Model {
	
	public function addCommon($act_id,$content,$user,$to_user=0){
		$sql = "INSERT INTO `zc_comment` (`comment_id`, `comment_text`, `comment_create_userid`, `comment_to_userid`, `huodong_id`, `comment_createtime`) 
		VALUES (NULL, '".$content."', '".$user."', '".$to_user."', '".$act_id."', NOW()) ";
		return $this->insert($sql);
	}
	
	public function getAllComment($act_id){
		$sql = "SELECT `zc_comment`.* ,user.*,touser.user_name AS touser_name  FROM  `zc_comment`
				LEFT JOIN `zc_user` AS user ON user . `id` = `zc_comment`.`comment_create_userid` 
				LEFT JOIN `zc_user` AS touser ON touser . `id` = `zc_comment`.`comment_to_userid` 
				WHERE  `zc_comment`.`huodong_id` =$act_id 
				ORDER BY `user_create_time` DESC
				";
		$result =  $this->fetchAll($sql);
		if( is_array($result) ){
			for( $i=0 ; $i < count($result); $i++ ) {
				$result[$i]["user_avatar"] = json_decode( $result[$i]["user_avatar"] );
			}
			return array( "list" => $result, "total"=>count($result) );
		}else{
			return array( "list" => array(), "total"=>"0" );
		}
	
	}
		
	public function del_comment($cid){
		$sql = "DELETE FROM `nkzc`.`zc_comment` WHERE `zc_comment`.`comment_id` = ".$cid;
		return $this->del($sql);
		
	}
	
	
	
}

?>