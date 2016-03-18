<?php
/**
*  Create On 2010-7-12
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/

namespace BF;

include_once("Smarty/Smarty.class.php");

class View {
	protected $_engine;
	protected $jsonArray = array("status"=>null, "statusInfo" => null, "data"=>null );
	protected $_tplData = array();
    protected $replaceWord = array();//需要被替换的文字
	protected $_globalVars = array();

	public function __construct($array){
		
 		$this->_engine = new \Smarty();
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
		$this -> replaceWord = $array["replace_word"];
        $this -> _globalVars = $array["global_var"];
		//print_r($this->_engine);
 	}

 	public function getEngine(){
 		return $this->_engine;
 	}
 	
 	public function __set($key,$val){
        $this->_tplData[$key] = $val;
 		// $this->_engine->assign($key,$val);
 	}

 	public function __get($key){
        return $this -> _tplData[$key];
 		// return $this->_engine->getTemplateVars($key);
 		// return $this->_engine->get_template_vars($key);
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
	
 	public function setMsg($val){
 		$this->jsonArray["statusInfo"]=$val;
 		return $this;
 	}
 	public function setStatus($val){
 		$this->jsonArray["status"]=$val;
 		return $this;
 	}
 	public function setData($val){
 		$this->jsonArray["data"]=$val;
 		return $this;
 	}
 	
 	public function render($name){
 		if ($name == "json"){
 			return $this -> preRender(json_encode($this->jsonArray));	
 		} 
        else if ($name == "debug") {
            return json_encode($this -> _tplData);
        }
        $this -> _engine -> assign($this -> _globalVars);
        $this -> _engine -> assign($this -> _tplData);
 		return $this->preRender( $this->_engine->fetch($name));
 	}

 	public function display($name){
        if ($name == 'json') {
            header("content-type:application/json;charset=utf-8");
        }
        else if ($name == 'debug') {
            header("content-type:text/html;charset=utf-8");
        }
        else {
            header("content-type:text/html;charset=utf-8");
        }

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
}
