<?php
include_once 'Util/CurlUtil.php';
include_once 'Push/CipherUtils.php';

class Push
{
	var $CLIENTID = "clientId";
	var $PLATFORM = "platform";
	var $TITLE = "title";
	var $CONTENT = "content";
	var $PARAM = "param";
	var $TOKENS = "tokens";
	var $PLATFORM_IPHONE = "iphone";
	var $PLATFORM_ANDROID = "android";
	var $pushServerUrl;
	var $pushKey;
	var $connectActionUrl;
	var $disConnectActionUrl;
	var $push2ClientActionUrl;
	var $push2TerminalsActionUrl;
	var $push2AllTermsUrl;
	
	public function __construct($pushUrl,$pKey)
	{
		$this->pushServerUrl = $pushUrl;
		$this->pushKey = $pKey;
		if ($this->pushServerUrl != null) {
			$this->connectActionUrl = $this->pushServerUrl.'/connect.do?';
			$this->disConnectActionUrl = $this->pushServerUrl.'/disconnect.do?';
			$this->push2ClientActionUrl = $this->pushServerUrl.'/push2Client.do?';
			$this->push2TerminalsActionUrl = $this->pushServerUrl.'/push2Terminals.do?';
			$this->push2AllTermsUrl = $this->pushServerUrl.'/push2AllTerms.do?';
		}
	}
	
	public function connect($codePusher)
	{
		$connectURL = $this->connectActionUrl."pusherId=".$codePusher;
		//echo $connectURL;
		$HttpUtil = new CurlUtil();
		$reponse = $HttpUtil->get($connectURL);
		//print_r($reponse);
		$array = array();
		$array = json_decode($reponse['info'],true);
		//print_r($array);
		if($array == null)
		{
			return null;
		}
		$state = $array['json']['state'];
		if ($state == 1) {
			return $array['json']['data']['ks'];
		}
		return null;
	}
	
	public function disconnect()
	{
		$disconnectURL = $this->disConnectActionUrl;
		try {
			$HttpUtil = new CurlUtil();
			$HttpUtil->get($disconnectURL);
		}catch (Exception $e)
		{
			echo $e;
		}
	}
	
	public function push2Terminals($codePusher, $tokens, $title, $content, $param)
	{
		$miwen = $this->connect($codePusher);
		$CipherUtils = new CipherUtils();
		//echo $miwen.'</br>';
		$list3 = array();
		$list3 = null;
		if ($miwen == null)
		{
			return $list3;
		}
		try {
			$key = $CipherUtils->unsymmetryDecrypt($miwen,$this->pushKey);
			//echo $key;
			if ($key == null)
			{
				return null;
			}
			$map = array();
			$map['tokens'] = $tokens;
			$map['title'] = $title;
			$map['content'] = $content;
			$json = "{\"json\":";
			$map['param'] = $json.json_encode($param)."}";
			//$map['param'] = $param;
			$list = array();
			$list = $this->sendURL($this->push2TerminalsActionUrl, $map, $key);
			$list3 = $list;
			//var_dump($list3);
		}catch(Exception $e){
			echo $e;
		}
		$this->disconnect();
		return $list3;
	}
	
	public function push2AllTerms($codePusher, $title, $content, $param)
	{
		$miwen = $this->connect($codePusher);
		$CipherUtils = new CipherUtils();
		$list3 = array();
		$list3 = null;
		if ($miwen == null)
		{
			return $list3;
		}
		try {
			$key = $CipherUtils->unsymmetryDecrypt($miwen,$this->pushKey);
			if ($key == null)
			{
				return null;
			}
			$map = array();
			$map['title'] = $title;
			$map['content'] = $content;
			$json = "{\"json\":";
			$map['param'] = $json.json_encode($param)."}";
			//echo $map['param'];
			$list = array();
			$list = $this->sendURL($this->push2AllTermsUrl, $map, $key);
			$list3 = $list;
		}catch (Exception $e)
		{
			echo $e;
		}
		$this->disconnect();
		return $list3;
	}
	
	public function sendURL($pushActionUrl,$maps,$key)
	{
		$returnList = array();
		$m = array();
		$m = $this->symmetryEncrypt($maps, $key);
		$HttpUtil = new CurlUtil();
		$post = $HttpUtil->post($pushActionUrl, $m);
		//print_r($post);
		$root = array();
		$root = json_decode($post['info'],true);
		if($root != null)
		{
			$state = $root['json']['state'];
			if ($state == 1)
			{
				$data = $root['json']['data'];
				$disActivityList = array();
				$disActivityList = $root['json']['data']['disActivityList'];
				if ($disActivityList != null)
				{
					for ($i = 0; $i < count($disActivityList); $i++)
					{
						$returnList[$i] = $disActivityList[$i]['activity'];
					}
				}
				else
				{
					$msg = $root['json']['msg'];
					echo $msg;
				}
			}
		}
		else
		{
			echo "接受jsonObject异常";
		}
		return $returnList;
	}
	
	public function symmetryEncrypt($map,$mKey)
	{
		//print_r($map);
		$m = array();
		$descoder = new CipherUtils();
		$m['action'] = "pushMessage";
		$keys = array();
		$vals = array();
		$keys = array_keys($map);
		$vals = array_values($map);
		for ($i = 0; $i < count($keys); $i++)
		{
			try {
				$val = $descoder->symmetryEncrypt($vals[$i], $mKey);
				$m[$keys[$i]]=$val;
			}catch (Exception $e)
			{
				echo $e;
			}
		}
		
		//print_r($m);
		return $m;
	}
}