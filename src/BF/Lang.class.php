<?php
namespace BF;

class Lang {
	
	protected $_data = array(); 
	
	
	public function __construct($langConf = array()){
		$this->setLangInfo($langConf);
	}
	
	public function setLangInfo($langConf){
		$this->_data = $langConf;
	}
	
	public function __set($key,$val){
		$this->_data[$key] = $val;
	}
	
	public function __get($key){
		return array_key_exists($key, $this->_data) ? $this->_data[$key] : $key;
	}
	
	
}

?>