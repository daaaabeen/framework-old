<?php
/**
*  Create On 2010-7-12
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/
class Db{
	protected $_dbConf = null;
	protected $_conn = array('master' => null, "slaver" => null );
	
	public function __construct($dbArr){
		$this->_dbConf["master"]["_host"]	=	$dbArr["master"]['host'];
		$this->_dbConf["master"]["_database"]	=	$dbArr["master"]['database'];
		$this->_dbConf["master"]["_user"]	=	$dbArr["master"]['user'];
		$this->_dbConf["master"]["_pw"]	=	$dbArr["master"]['pw'];
		$this->_dbConf["master"]["_charset"]	=	$dbArr["master"]['charset'];
		
		$this->_dbConf["slaver"]["_host"]	=	$dbArr["slaver"]['host'];
		$this->_dbConf["slaver"]["_database"]	=	$dbArr["slaver"]['database'];
		$this->_dbConf["slaver"]["_user"]	=	$dbArr["slaver"]['user'];
		$this->_dbConf["slaver"]["_pw"]	=	$dbArr["slaver"]['pw'];
		$this->_dbConf["slaver"]["_charset"]	=	$dbArr["slaver"]['charset'];
	}
	/**
	* 连接所需的数据库
	*/
	function connect($masterOrSlaver){
		$conn = mysql_connect($this->_dbConf[$masterOrSlaver]["_host"], $this->_dbConf[$masterOrSlaver]["_user"], $this->_dbConf[$masterOrSlaver]["_pw"], true) or die("connect error!");
		mysql_select_db($this->_dbConf[$masterOrSlaver]["_database"], $conn)or die('error '.$this->_dbConf[$masterOrSlaver]["_database"].mysql_error());
		mysql_query("set names " . $this->_dbConf[$masterOrSlaver]["_charset"]);
		$this->_conn[$masterOrSlaver]=$conn;
	}
	
	/**
	**
	**returnbool true/false
	*/
	public function del($sql){
		if( !$this->_conn["master"] )$this->connect("master");
		$result = mysql_query($sql,$this->getConn("master"));
		return $result;
	}
	/**
	**途薷菘械一
	**returnbool true/false
	*/
	public function update($sql){
		if( !$this->_conn["master"] )$this->connect("master");
		$result = mysql_query($sql,$this->getConn("master"));
		return $result;
	}
	/**
	**途菘胁一
	**return晒id失芊false
	*/
	public function insert($sql){
		if( !$this->_conn["master"] )$this->connect("master");
		$result = mysql_query($sql,$this->getConn("master"));
		if(!$result)return false;
		$lastId = mysql_insert_id();
		return $lastId;
	}
	/**
	**途菘选一
	**return一维array
	*/
	
	public function fetchRow($sql){
		if( !$this->_conn["slaver"] )$this->connect("slaver");
		$result = mysql_query($sql,$this->getConn("slaver"));
		if($result){
			$row = mysql_fetch_assoc($result);
			return $row;
		}
		return false;
	}
	
	/**
	**途菘选
	**return维array
	*/		
	public function fetchAll($sql){
		if( !$this->_conn["slaver"] )$this->connect("slaver");
		$result = mysql_query($sql,$this->getConn("slaver"));
		if($result &&  mysql_num_rows($result) > 0 ){
			mysql_data_seek($result, 0);
			while ($row = mysql_fetch_assoc($result))
			{
				$output[] = $row;
			}
			mysql_free_result($result);
			return $output;
		}
		return false;
	}
	
	/**
	**鏌ヨ鏁伴噺
	**pram $table()
	**return int
	*/
	public function getTotal($table,$filter=null){
		
		$return = 0;
		$sql="select count(*) as num from `".$table."` ";
		if($filter){
			$sql.="WHERE ".$filter." ";
		}
		if( $row = $this->fetchRow($sql) ) $return = $row["num"];

		return $return;
	}
		
	/**
	**乇菘
	*/
	public function getConn($masterOrSlaver){
		return $this->_conn[$masterOrSlaver];
	}
	
	
	public function __distruct(){
		if($this->_conn["master"])mysql_close($this->_conn["master"]);
		if($this->_conn["slaver"])mysql_close($this->_conn["slaver"]);
	}
}