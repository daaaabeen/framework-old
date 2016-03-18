<?php
class activity extends \Been\Model{
	
	protected $_typeList = array(
						array("code"=>1,"name"=>"公益"),
						array("code"=>2,"name"=>"文体"),
						array("code"=>3,"name"=>"学术"),
						array("code"=>4,"name"=>"文化创意"),
						array("code"=>5,"name"=>"实践调研"),
						array("code"=>6,"name"=>"就业创业"), 
						array("code"=>7,"name"=>"其它")							
					);
	/**
	 * 获取类别列表
	 * Enter description here ...
	 */
	public function getTypeList(){
		return $this->_typeList;
	}

	
	 
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $type 类别
	 * @param unknown_type $state 审核 0不限  1未审核  2审核通过  3审核不通过
	 * @param unknown_type $end 0-不限  1-未完成进行中，2-已完成
	 * @param unknown_type $page
	 * @param unknown_type $num
	 * @param unknown_type $userid
	 */
	public function getactivitylist($type, $state, $end, $page=1, $num=10, $order = "date" , $userid = -1){
		//select *,if(sva=1,"男","女") as ssva from taname where sva<>""
		$sql = "SELECT DISTINCT  `zc_huodong`.*, `picture`.* ,`zc_user`.`user_name`, if(`zc_like`.`user_id` = ".$userid." ,1,0 ) as liked FROM `zc_huodong` 
				LEFT JOIN `zc_like` ON `zc_like`.`huodong_id` = `zc_huodong`.`id` AND `zc_like`.`user_id` = ".$userid." 
				LEFT JOIN `picture` ON `picture`.`pic_id` = `zc_huodong`.`pic_id`
				LEFT JOIN `zc_user` On `zc_user`.`id` = `zc_huodong`.`faburen` ";
		$where = " WHERE ";
		$filter = " 1 ";
		if($state){
			$filter .= " AND `shenhe` = " .$state;
		}
		
		if($type){
			$filter .= " AND `leibie` = " .$type;
		}
		if($end){//1-未完成
			$filter .= " AND `wancheng` =" .$end;
		}
		if($order == "money"){
			$orderby = " ORDER BY `zc_huodong`.`zijin` DESC ";
		}elseif($order == "zan" ){
			$orderby = " ORDER BY `zc_huodong`.`zan` DESC ";
		}else{
			$orderby = " ORDER BY `zc_huodong`.`fabushijian` DESC ";
		}
		
		
		$limit = "  LIMIT " . ($page - 1) * $num . ", " . $num . "";
		
		$list = $this->fetchAll ( $sql . $where . $filter . $orderby .$limit );
		
		$total = $this->getTotal("zc_huodong",$filter);
		$totalPage = ceil ( $total / $num );
		//echo $sql . $where . $filter .$limit ;
		
		
		return array (
				'page' => $page,
				'list' => $list,
				'total' => $total,
				'totalPage' => $totalPage
		);
	}
	
	public function getMySupport($userid,$state,$page,$num=10){
		
		//select *,if(sva=1,"男","女") as ssva from taname where sva<>""
		$sql = "SELECT `zc_like`.*, `zc_huodong`.*  FROM `zc_huodong` , `zc_like` ";
		$where = " WHERE ";
		$filter = " `zc_like`.`huodong_id` = `zc_huodong`.`id` AND `zc_like`.`user_id` = ".$userid." ";
		if($state){
			$filter .= " AND `zc_huodong`.`wancheng` =" .$state;
		}
		$orderby = " ORDER BY `zc_like`.`like_time` DESC ";
		$limit = "  LIMIT " . ($page - 1) * $num . ", " . $num . "";
		//echo  $sql . $where . $filter . $orderby .$limit ;
		
		$total = $this->getSupportCount($userid);
		//echo $total;
		$totalPage = ceil ( $total / $num );
		
		$list = $this->fetchAll ( $sql . $where . $filter . $orderby .$limit );
		return array (
				'page' => $page,
				'list' => $list,
				'total' => $total,
				'totalPage' => $totalPage
		);
	}
	
	public function getSupportCount($userid){
		$sql = "SELECT count(*) as num FROM zc_like,zc_huodong WHERE `zc_like`.`huodong_id` = `zc_huodong`.`id` AND `zc_like`.`user_id` = ".$userid." ";;
		//echo $sql;
		$result = $this->fetchRow($sql);
		//print_r($result);
		return $result["num"];
	}
	
public function getMyAct($userid,$state,$page,$num=10){
		
		//select *,if(sva=1,"男","女") as ssva from taname where sva<>""
		$sql = "SELECT  `zc_huodong`.*  FROM `zc_huodong` ";
		$where = " WHERE ";
		$filter = " `zc_huodong`.`faburen` = ".$userid." ";
		if($state){
			$filter .= " AND `zc_huodong`.`wancheng` =" .$state;
		}
		$orderby = " ORDER BY `zc_huodong`.`fabushijian` DESC ";
		$limit = "  LIMIT " . ($page - 1) * $num . ", " . $num . "";
		//echo  $sql . $where . $filter . $orderby .$limit ;
		
		$total = $this->getMyactCount($userid);
		//echo $total;
		$totalPage = ceil ( $total / $num );
		
		$list = $this->fetchAll ( $sql . $where . $filter . $orderby .$limit );
		return array (
				'page' => $page,
				'list' => $list,
				'total' => $total,
				'totalPage' => $totalPage
		);
	}
	
	public function getMyactCount($userid){
		$sql = "SELECT count(*) as num FROM zc_huodong WHERE `faburen` = ".$userid." ";
		$result = $this->fetchRow($sql);
		//print_r($result);
		return $result["num"];
	}
	
	/**
	 * 获取满足条件的活动数目
	 * Enter description here ...
	 * @param unknown_type $type
	 * @param unknown_type $state 审核 0不限  1未审核  2审核通过  3审核不通过
	 * @param unknown_type $end 0所有 1进行中 2已完成 3已失败
	 */
	public function getActNum($type, $state, $end ){
		$filter = " 1 ";
		if($state){
			$filter .= " AND `shenhe` = " .$state;
		}
		if($type){
			$filter .= " AND `leibie` = " .$type;
		}
		if($end){//1-未完成
			$filter .= " AND `wancheng` =" .$end;
		}
		return $this->getTotal("zc_huodong",$filter);
	}
	
	
	public function delact($id){
		$sql = "DELETE FROM `zc_huodong` WHERE `id` = " .$id;
		$this->del($sql);
		$sql = "DELETE FROM `zc_comment` WHERE `huodong_id` = ".$id;
		$this->del($sql);
		return true;
	}
	
	public function actdetail($id){
		$sql = "SELECT `zc_huodong`.*,`zc_user`.`user_name` ,`picture`.* FROM `zc_huodong`
				LEFT JOIN `zc_user` On `zc_user`.`id` = `zc_huodong`.`faburen`
				LEFT JOIN `picture` ON `picture`.`pic_id` = `zc_huodong`.`pic_id`
				WHERE `zc_huodong`.`id` = " .$id;
		//echo $sql;
		return $this->fetchRow($sql);
	}
	

	public function approve($id){
		$sql = "UPDATE `zc_huodong` SET `shenhe` = 2
				WHERE `id` = " .$id;
		return $this->update($sql);
	}
	
	public function appr($id){
		$sql = "UPDATE `zc_huodong` SET `ganyan_state` = 2
				WHERE `id` = " .$id;
		return $this->update($sql);
	}
	
	public function reject($id){
		$sql = "UPDATE `zc_huodong` SET `shenhe` = 3
				WHERE `id` = " .$id;
		return $this->update($sql);
	}
	
	public function reje($id){
		$sql = "UPDATE `zc_huodong` SET `ganyan_state` = 3
				WHERE `id` = " .$id;
		return $this->update($sql);
	}
	
	public function finishiact($id){
		$sql = "UPDATE `zc_huodong` SET `wancheng` = 2
				WHERE `id` = " .$id;
		return $this->update($sql);
	}
	
	public  function failed($id){
		$sql = "UPDATE `zc_huodong` SET `wancheng` = 3
				WHERE `id` = " .$id;
		return $this->update($sql);
	}
	
	public function addact($biaoti, $fuzeren, $xuehao, $dianhua, $neirong, $yiyi, $pic_id, $zijin, $shijian, $didian, $leibie ,$faburen){
		$sql = "INSERT INTO `zc_huodong` (`biaoti`,`fuzeren`,`xuehao`,`dianhua`,`neirong`,`yiyi`,`pic_id`,`zijin`,`shijian`,`didian`,`leibie`,`faburen`,`shenhe`,`wancheng`,`fabushijian`)
				VALUES ('".$biaoti."','".$fuzeren."','".$xuehao."','".$dianhua."','".$neirong."','".$yiyi."','".$pic_id."','".$zijin."','".$shijian."','".$didian."','".$leibie."','".$faburen."',1,1,NOW())";	
		//echo $sql;
		return $this->insert($sql);
	}
	
	public function modify($id,$data,$user = -1){
		
		$sql = "UPDATE `nkzc`.`zc_huodong` SET 
				`biaoti` = '".$data['name']."', 
				`fuzeren` = '".$data['charge']."', 
				`xuehao` = '".$data['number']."', 
				`dianhua` = '".$data['phone']."', 
				`neirong` = '".$data['content']."', 
				`yiyi` = '".$data['intro']."',
				`pic_id` = '".$data['picid']."', 
				`zijin` = '".$data['price']."', 
				`shijian` = '".$data['time']."', 
				`didian` = '".$data['addr']."', 
				`leibie` = '".$data['type']."' 
				WHERE `zc_huodong`.`id` = ".$id;
		if($user > 0){
			$sql .= " AND `faburen` = ".$user; 
		}
		//echo $sql;
		return $this->update($sql);
	}

	/**
	 * 赞！
	 * Enter description here ...
	 * @param unknown_type $userid
	 * @param unknown_type $actid
	 * @return 0 失败 -1 已经赞过 -2不是进行中的活动  1 成功
	 */
	public function likeAct($userid,$actid){
		if($act_detail = $this->actdetail($actid)){
			if( $act_detail["wancheng"] != 1 ){
				return -2;
			}
		}else{
			return 0;
		}
		
		if($this->haveLiked( $userid, $actid )){
			return -1;
		}else{
			$sql = "INSERT INTO `nkzc`.`zc_like` (`user_id`, `huodong_id`, `like_time`) VALUES ('".$userid."', '".$actid."', NOW())";
			$this->insert($sql);
			$sql = "UPDATE  `nkzc`.`zc_huodong` SET  `zan` =  `zan`+1 WHERE  `zc_huodong`.`id` =".$actid;
			$this->update($sql);
			$sql = "SELECT `zc_huodong`.`zan` FROM `zc_huodong`	WHERE `zc_huodong`.`id` = " .$actid;
			$result = $this->fetchRow($sql);
			return $result["zan"];
		}
	}
	
	/**
	 * 后台加赞的接口
	 * Enter description here ...
	 * @param unknown_type $actid
	 */
	public function add_like($actid){
		$sql = "UPDATE  `nkzc`.`zc_huodong` SET  `zan` =  `zan`+1 WHERE  `zc_huodong`.`id` =".$actid;
		$this->update($sql);
		$sql = "SELECT `zc_huodong`.`zan` FROM `zc_huodong`	WHERE `zc_huodong`.`id` = " .$actid;
		$result = $this->fetchRow($sql);
		return $result["zan"];
	}
	
	/**
	 * 检查是否赞过
	 * Enter description here ...
	 * @param unknown_type $userid
	 * @param unknown_type $actid
	 */
	public function haveLiked($userid,$actid){
		$sql = "SELECT * FROM  `zc_like` WHERE `user_id`=".$userid." AND `huodong_id` = ".$actid." ";
		//echo $sql;
		return $this->fetchRow($sql) ? true : false;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $actid
	 * @param unknown_type $shenhe 审核 0不限  1未审核  2审核通过  3审核不通过
	 * 
	 */
	public function gerActFromId($actid,$shenhe = 0,$userid=-1){
		$sql = "SELECT `zc_huodong`.*,`zc_user`.`user_name`,`zc_user`.`renren_id`,`picture`.* , if(`zc_like`.`user_id` = ".$userid." ,1,0 ) as liked  FROM  `zc_huodong` 
				 LEFT JOIN `zc_like` ON `zc_like`.`huodong_id` = `zc_huodong`.`id` AND `zc_like`.`user_id` = ".$userid." 
				 LEFT JOIN `zc_user` ON `zc_user`.`id` = `zc_huodong`.`faburen` 
				 LEFT JOIN `picture` ON `picture`.`pic_id` = `zc_huodong`.`pic_id`  
				 WHERE  `zc_huodong`.`id` =".$actid." ";
		if($shenhe != 0){
			$sql .= " AND ( `zc_huodong`.`shenhe` = ".$shenhe." OR `zc_huodong`.`faburen` = ".$userid." )";
		}
		
		
		//echo $sql;
		if($result = $this->fetchRow($sql)){
			$result["type_name"] = $this->typecode2name($result["leibie"]);
			return $result;
		}
		return false;
	}
	
	/**
	 * 类别代码获取类别名称
	 * Enter description here ...
	 * @param unknown_type $code
	 */
	public function typecode2name($code){
		foreach($this->_typeList as $item){
			if($item["code"] == $code){
				return $item["name"];
			}
		}
		return false;
	}
	
	public function getactpic($id){
		$sql = "SELECT `zc_huodong_tupian`.* ,`picture`.* FROM `zc_huodong_tupian`
				LEFT JOIN `picture` ON `picture`.`pic_id` = `zc_huodong_tupian`.`pic_id`
				WHERE `zc_huodong_tupian`.`huodong_id` = " .$id;
		return $this->fetchAll($sql);
	
	}
	
	public function add_act_pic( $act_id , $id_list = null){
		$sql = "DELETE FROM `zc_huodong_tupian` WHERE `zc_huodong_tupian`.`huodong_id` = ".$act_id;
		$this->del($sql);
		if( is_array($id_list) ){
			$sql = "INSERT INTO `zc_huodong_tupian` (`id`, `huodong_id`, `pic_id`) VALUES ";
			$sql .= " (NULL, '".$act_id."', '".$id_list[0]."') ";
			for($i = 1 ; $i < count($id_list) ; $i++ ){
				$sql .= " ,(NULL, '".$act_id."', '".$id_list[$i]."') ";
			}
			//echo $sql;
			return $this->insert($sql);
		}else if(is_integer($id_list)){
			$sql = "INSERT INTO `zc_huodong_tupian` (`id`, `huodong_id`, `pic_id`) 
						VALUES (NULL, '".$act_id."', '".$id_list."') ; ";
			return $this->insert($sql);
		}else{
			return true;
		}
	}
	
	public function add_act_ganyan( $act_id , $ganyan ){
		$sql = "UPDATE `zc_huodong` SET  `ganyan` =  '".$ganyan."', `ganyan_state` =  '1' WHERE  `zc_huodong`.`id` =".$act_id;
		return $this->update($sql);
	}
	
	
	
}