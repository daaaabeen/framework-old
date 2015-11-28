<?php
/**
*  Create On 2010-7-12
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/
define('SMARTY_SPL_AUTOLOAD',1);
include_once("Smarty/Smarty.class.php");
class View{
	protected $_engine;
	protected $jsonArray = array("json" => array( "state" => null, "msg"=>null, "status"=>null, "data"=>null ) );
	protected $replaceWord = array();//需要被替换的文字
	
	public function __construct($array){
		
 		$this->_engine=new Smarty();
 		$this->_engine->left_delimiter 	= 	$array['left_delimiter'];
		$this->_engine->right_delimiter = 	$array['right_delimiter'];
 		$this->_engine->template_dir	=	$array['template_dir'];
		$this->_engine->compile_dir		=	$array['compile_dir'];
		$this->_engine->caching 		= 	$array['caching'];
		$this->_engine->plugins_dir		=	array( dirname(__FILE__).'/View/plugins',dirname(__FILE__).'/Smarty/plugins' );
		if($array['caching']){
			$this->_engine->cache_lifetime 	= 	$array['cache_lifetime']?$array['cache_lifetime']:3600;
			$this->_engine->cache_dir		=	$array['cache_dir'];
		}
		$this->replaceWord = $array["replace_word"];
		//print_r($this->_engine);
 	}

 	public function getEngine(){
 		return $this->_engine;
 	}
 	
 	public function __set($key,$val){
 		$this->_engine->assign($key,$val);
 	}

 	public function __get($key){
 		return $this->_engine->getTemplateVars($key);
 		//return $this->_engine->get_template_vars($key);
 	}

 	public function __isset($key){
 		return $this->_engine->getTemplateVars($key) !== null;
 	}

 	public function __unset($key){
 		//return $this->_engine->clear_assign($key);
 		return $this->_engine->clearAssign($key);
 	}

 	public function assign($spec,$value=null){
 		if(is_array($spec)){
 			$this->_engine->assign($spec);
 			return;
 		}

 		$this->_engine->assign($spec,$value);
 	}

 	public function clearVars(){
 		$this->_engine->clear_all_assign();
 	}
	
 	public function setState($val){
 		$this->jsonArray["json"]["state"]=$val;
 		return $this;
 	}
 	public function setMsg($val){
 		$this->jsonArray["json"]["msg"]=$val;
 		return $this;
 	}
 	public function setStatus($val){
 		$this->jsonArray["json"]["status"]=$val;
 		return $this;
 	}
 	public function setData($val){
 		$this->jsonArray["json"]["data"]=$val;
 		return $this;
 	}
 	
 	public function render($name){
 		if($name=="json"){
 			return $this->preRender(json_encode($this->jsonArray));	
 		}
 		$cname=strtolower($this->getApp()->getRequest()->cName);	
 		return $this->preRender( $this->_engine->fetch($cname."/".strtolower($name)) );
 	}

 	public function display($name){
 		echo $this->render($name);
 	}
 	
 	/**
 	 * 在渲染之前对数据进行处理
 	 * 替换一些不该出现的文字
 	 */
 	protected function preRender($str){
 		
 		foreach($this->replaceWord as $key => $val ){
 			$str = str_replace($val,$key,$str);
 		}
 		return $str;
 	}
	/**
	 * 获取应用
	 * @return unknown_type
	 */
	public function getApp(){
		if (null !== $this->_app) {
            return $this->_app;
        }
        
        if (class_exists('App')) {
            $this->_app = App::getInstance();
            return $this->_app;
        }
        else{
        	exit("没有App.class.php类这个文件");
        }
	}
	/**
	 * 
	 * @return unknown_type
	 */
	public function getRequest(){
		return $this->getApp()->getRequest();	
	}
}