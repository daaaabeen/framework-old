<?php
/**
*  Create On 2010-8-21
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/
class Request
{
	public $mName;//模块名字
	public $cName;//controlle名字
	public $aName;//action名字
	
	/**
	 * 应用名字与应用所在文件夹相同
	 * 如 app
	 */
	public $appName;
	
	/**
	 * 应用的网站路径
	 * 如 http://localhost/nkjob/admin
	 */
	public $appPath;
	
	/**
	 * 带php文件的网站地址
	 * 如 http://localhost/nkjob/admin.php
	 */
	public $phpUrl;
	
	/**
	 * 不代php文件的网站地址
	 * 如 http://localhost/nkjob
	 */
	public $hostUrl;
	
	protected $_get =array(); //get请求的数据
	
	public function __construct(){
		$this->getRequestFromURL();
	}
	
	
	/**
     * 从url中获取用户的请求参数
     * 如 http://localhost/nkjob/admin.php/controllername/actionname/key1/value1/key2/value2
     * 
     */
	protected function getRequestFromURL(){
		//print_r($_SERVER);
		$pathStr = dirname($_SERVER["SCRIPT_NAME"]);
		//echo $_SERVER["REQUEST_URI"];
		$len = strlen($pathStr);
		
		$filter_param = array('<','>','"',"'",'%3C','%3E','%22','%27','%3c','%3e');
		$uri = str_replace($filter_param, '', $_SERVER['REQUEST_URI']);
		//$uri = str_replace("//", '/', $uri);
		$posi = strpos($uri, '?');
		if ($posi) $uri = substr($uri,0,$posi);
		
		$paths =explode('/', str_replace("//", '/', trim( substr( $uri, $len ), '/') ) ) ;
		//$paths = array_key_exists('PATH_INFO', $_SERVER) ? explode('/', trim($_SERVER['PATH_INFO'], '/') ) : array();
		//print_r($paths);
		//exit();
		if( array_key_exists(0,$paths) ){
			$this->mName = strtolower(array_shift($paths));
		}else $this->mName = "index";
		
		if(array_key_exists(0,$paths))	$this->cName = ucwords(strtolower(array_shift($paths)));
		else $this->cName = "Index";
		
		if(array_key_exists(0,$paths))	$this->aName = ucwords(strtolower(array_shift($paths)));
		else $this->aName = "Index";
		
		while( array_key_exists(0,$paths) ){
			$this->_get[array_shift($paths)] = array_shift($paths);
		}
		
		
		//print_r($_SERVER);
		if($_SERVER['SERVER_PORT'] == 80){
			$this->phpUrl="http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
		}else {
			$this->phpUrl="http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'];
		}
		$this->hostUrl=dirname($this->phpUrl);
		$this->appName=APPNAME;
		$this->appPath=($this->hostUrl).'/'.($this->appName);
		//echo $this->cName,$this->aName;
		return $this;
	}
	
	/**
	 * 根据key获取传来的数据的值 如果没有返回null
	 * @param unknown_type $key
	 * @return string or NULL
	 */
	public function get($key){
		return array_key_exists($key, $this->_get) ? $this->_get[$key] : ( array_key_exists($key,$_POST) ? $_POST[$key] : ( array_key_exists($key,$_GET) ? $_GET[$key] : null ) );
	}
}