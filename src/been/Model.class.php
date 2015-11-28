<?php
/**
*  Create On 2010-7-12
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/
class Model{
	protected $_db;
	protected $_dbr;
	public function __construct(Db $db=NULL){
		if($db==NULL) $this->_db=App::getInstance()->getDb();
		else $this->_db=$db;
		
	}
	
	
	
	public function getDb(){
		return $this->_db;
	}
	/**
	**returnbool true/false
	*/
	public function del($sql){
		return $this->_db->del($sql);
	}
	/**
	* 更新
	* return bool true/false
	*/
	public function update($sql){
		return $this->_db->update($sql);
	}
	/**
	**插入一条数据
	**return id or false
	*/
	public function insert($sql){
		return $this->_db->insert($sql);
	}
	/**
	** 选取一条数据
	** return false / array
	*/
	public function fetchRow($sql){
		return $this->_db->fetchRow($sql);
	}
	
	/**
	**返回多条数据
	**return array
	*/		
	public function fetchAll($sql){
		return $this->_db->fetchAll($sql);
	}
	
	/**
	* 查询符合某个条件数据在某个表中的条数
	* @param $table $filter
	* @return int
	*/
	public function getTotal($table,$filter=NULL){
		
		return $this->_db->getTotal($table,$filter);
	}
	public function getTotalFromSql($sql){
	
		return $this->_db->getTotalFromSql($sql);
	}
/**
	 * 过滤字符串防止被恶意代码攻击
	 * @param 要过滤的字符串
	 * @return 过滤后的字符串
	 */
	public function filterSomeBadTag($str){
 
		if (!get_magic_quotes_gpc()) {    // 判断magic_quotes_gpc是否为打开  
			$str = addslashes($str);    // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤  
		}  
		$str = str_replace("_", "\_", $str);    // 把 '_'过滤掉  
		$str = str_replace("%", "\%", $str);    // 把 '%'过滤掉  
		$str = nl2br($str);    // 回车转换  
		$str = htmlspecialchars($str);    // html标记转换  
		return $str;    
	}
	
	/**
	 * 检查是否含有非法字符
	 * @param unknown_type $sql_str
	 * @return boolean
	 */
	public function haveInjectTag($sql_str){
		return eregi('select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);
	}
	
	
	/**
	 * 检查是否含有非法字符
	 * @param unknown_type $sql_str
	 * @return boolean
	 */
	function haveBadTag($sql_str) { //防止注入
		return preg_match('/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/', $sql_str); 
	}
	
	/**
	 *
	 * @param unknown_type $ts
	 * @return string
	 */
	public function Timestamp2Datetime($ts){
		return date("Y-m-d H:i:s",$ts);
	}
	
}