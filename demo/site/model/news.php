<?php
class news extends Model{
	
	/**
	 * 获取新闻列表
	 * Enter description here ...
	 * @param unknown_type $page
	 * @param unknown_type $num
	 */
	public function get_news_list($page=1, $num=10){
		$sql = " SELECT `zc_xinwen`.*, `file`.* FROM  `zc_xinwen` "
			   ." LEFT JOIN `file` ON `file`.file_id = `zc_xinwen`.`file_id` ";
		$where = " WHERE ";
		$filter = " 1 ";
		$limit = "  LIMIT " . ($page - 1) * $num . ", " . $num . "";
		
		$list = $this->fetchAll ( $sql.$where.$filter.$limit );
		
		$total = $this->getTotal("zc_xinwen",$filter);
		$totalPage = ceil ( $total / $num );
		return array (
				'page' => $page,
				'list' => $list,
				'total' => $total,
				'totalPage' => $totalPage
		);
	}
	

	/**
	 * 添加新闻
	 * @param unknown_type $biaoti
	 * @param unknown_type $fileid
	 * @param unknown_type $neirong
	 * @param unknown_type $faburen
	 * @return Ambigous <boolean, number>
	 */
	public function addnews($biaoti, $fileid, $neirong, $faburen){
		$sql = "INSERT INTO `zc_xinwen` ( `xw_biaoti`, `file_id`, `xw_neirong`, `xw_shijian`, `xw_faburen` ) VALUES ( '".$biaoti."', '".$fileid."' , '".$neirong."', NOW(), '".$faburen."')";
		return $this->insert($sql);
	} 
	

	public function get_news_from_id( $id ){
		$sql = $sql = " SELECT `zc_xinwen`.*, `file`.* FROM  `zc_xinwen` "
			   ." LEFT JOIN `file` ON `file`.file_id = `zc_xinwen`.`file_id` ";
		$where = " WHERE ";
		$filter = " `zc_xinwen`.`xw_id` = ".$id;
		$sql .= $where.$filter;
		return $this->fetchRow($sql); 
	}
	
	public function delnews($id){
		$sql = "DELETE FROM `zc_xinwen` WHERE `xw_id` = " .$id;
		return $this->del($sql);
	}
	
	public function editnews($id, $biaoti, $neirong, $fileid = NULL){
		if($fileid){
			$sql = "UPDATE `zc_xinwen` SET
					`xw_biaoti` = '".$biaoti."',
					`xw_neirong` = '".$neirong."',
					`file_id` = '".$fileid."'
					WHERE `xw_id` = " .$id ;
		}
		else{
			$sql = "UPDATE `zc_xinwen` SET
					`xw_biaoti` = '".$biaoti."',
					`xw_neirong` = '".$neirong."'
					WHERE `xw_id` = " .$id ;
		}
		return $this->update($sql);
		
	}
	
}