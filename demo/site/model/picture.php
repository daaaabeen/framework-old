<?php
class picture extends \Been\Model{
	
	/**
	 * 添加图片
	 * @param unknown_type $pictype
	 * @param unknown_type $piclink
	 */
	public function addPic($pictype,$piclink)
	{
		$sql = "INSERT INTO `nkzc`.`picture` (`pic_id`, `pic_type`, `pic_link`, `pic_state`) VALUES (NULL, '".$pictype."', '".$piclink."', '0');";
// 		echo $sql;
		return $this->insert($sql);
	}
	
	public function frontpic($picid,$url){
		$sql = "INSERT INTO `nkzc`.`frontpic` (`pic_id`, `picstate`, `fp_url`) VALUES ('".$picid."' , 2, '".$url."');";
		 		//echo $sql;
		return $this->insert($sql);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param $state 空是所有 1是启用的 2是未启用的
	 */
	public function getfrontpic($state=null){
		if($state==null){
			$sql = "SELECT `picture`.* , `frontpic`.* FROM `frontpic`
				LEFT JOIN `picture` ON `picture`.`pic_id` = `frontpic`.`pic_id`
				ORDER BY `frontpic`.`picstate` DESC";
		}else{
			$sql = "SELECT `picture`.* , `frontpic`.* FROM `frontpic`
				LEFT JOIN `picture` ON `picture`.`pic_id` = `frontpic`.`pic_id` 
				WHERE `picstate` = ".$state." 
				ORDER BY `frontpic`.`picstate` DESC";
		}
		
		return $this->fetchAll($sql);
	}
	
	public function delfrontpic($picid){
		$sql = "DELETE FROM `frontpic` WHERE `fp_id` = " .$picid;
		//echo $sql;
		return $this->del($sql);
	}
	
	public function enablepic($id){
		$sql = "UPDATE `frontpic` SET `picstate` = 1
				WHERE `fp_id` = " .$id;
		return $this->update($sql);
	}
	
	public function disablepic($id){
		$sql = "UPDATE `frontpic` SET `picstate` = 2
				WHERE `fp_id` = " .$id;
		return $this->update($sql);
	}
	
//-----------------------------------------------------------------------	
	/**
	 * 删除图片
	 * @param unknown_type $pid
	 * @return resource
	 */
	public function delpic($pid)
	{
		$sql = "DELETE FROM `howdo`.`picture` WHERE `picture`.`pic_id` = '".$pid."'";
		//echo $sql;
		return $this->del($sql);
	}
	
	/**
	 * 图片设置启用
	 * @param unknown_type $pid
	 * @return resource
	 */
	public function setPictureState($pid){
		$sql = "UPDATE `howdo`.`picture` SET `pic_state` = '1' WHERE `picture`.`pic_id` = '".$pid."';";
		//echo $sql;
		return $this->update($sql);
	}
	
	
	
}