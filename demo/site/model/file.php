<?php
class file extends \Been\Model
{
	public function addfile($name, $link, $type)
	{
		$sql = "INSERT INTO `file` (`file_id`,`file_name`,`file_link`,`file_type`) VALUES (NULL, '".$name."', '".$link."', '".$type."')";
		//echo $sql;
		return $this->insert($sql);
	}
	
	public function delFile($fileID){
		$sql = "DELETE FROM `file` WHERE `file`.`file_id` = '".$fileID."'";
		return $this->del($sql);
	}
}