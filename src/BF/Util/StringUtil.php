<?php
/**
*  
*  Create On 2013-9-10����12:00:57
*  Author lidianbin
*  QQ: 281443751
*  Email: lidianbin@iwind-tech.com
**/
class StringUtil{
	
	//截取字符串
	public function cut_str_utf($string, $length,$adder="…") {
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info);
		$wordscut="";
		$j=0;
		for($i=0; $i<count($info[0]); $i++) {
			$wordscut .= $info[0][$i];
			$j = ord($info[0][$i]) > 127 ? $j + 2 : $j + 1;
			if ($j > $length - 1) {
				return $wordscut.$adder;
			}
		}
		return join('', $info[0]);
	}
	
	public function is_email($val){
		$reg='/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/';
	
		return preg_match($reg, $val);
	}
	
	public function is_phone($val){
		return preg_match('/^1[3458][0-9]{9}$/',$val);
	}
	
}
