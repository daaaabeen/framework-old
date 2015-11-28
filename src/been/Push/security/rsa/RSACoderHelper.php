<?php
class RSACoderHelper{
	private $p;
	private $q;
	
	 public function __construct()
	 {
	 	$this->p = 0;
	 	$this->p = 0;
	 }
	 
	 private function primenumber($num)
	 {
	 	$k = 0;
	 	$k = sqrt($num);
	 	$flag = true;
	 	$i = 2;
	 	do 
	 	{
	 		if($i > $k)
	 		{
	 			break;
	 		}
	 		if($num % $i == 0 )
	 		{
	 			$flag = false;
	 			break;
	 		}
	 		$i++;
	 	}while (true);
	 	return $flag;
	 }
	 
	 private function bigprimeRandom()
	 {
	 	
	 	$m = pow(10, 17);
	 	do {
	 		$k = $this->randomKeys(16) / $m;
	 		$p = number_format($k * 1000000, 0,'','');
	 	}while (!$this->primenumber($p));
	 	do {
	 		$y = $this->randomKeys(16) / $m;
	 		$q = number_format($y * 1000000, 0,'','');
	 	}while ($p == $q || !$this->primenumber($q));
	 }
	
	 private function inputPQ()
	 {
	 	$key = array();
	 	$this->bigprimeRandom();
	 	$key[0] = $this->p * $this->q;
	 	$key[1] = ($this->p - 1) * ($this->q - 1);
	 	return $key;
	 }
	 
	 private function gcd($a, $b)
	 {
	 	if($b == 0)
	 	{
	 		$gcd = a;
	 	}else
	 	{
	 		$gcd = $this->gcd($b, $a % $b);
	 	}
	 	return $gcd;
	 }
	 
	 private function getPublicKey($m)
	 {
	 	$publicKey = 0;
	 	$m = pow(10, 17);
	 	do {
	 		$k = $this->randomKeys(16) / $m;
	 		$publicKey = number_format($k * 1000000, 0,'','');
	 	}while ($publicKey >= $m || $this->gcd($m, $publicKey) != 1 );	
	 	return $publicKey; 	
	 }
	 
	 private function  getPrivateKey($publicKey, $m)
	 {
	 	$value = 1;
	 	$privateKey = 0;
	 	$i = 1;
	 	do {
	 		$value = $i * $m + 1;
	 		if ($value % $publicKey == 0 && $value / $publicKey < m)
	 		{
	 			$privateKey = $value / $publicKey;
	 			break;
	 		}
	 		$i++;
	 	}while (true);
	 	return $privateKey;
	 }
	 
	 private function colum($y,$key,$n)
	 {
	 	return bcpowmod($y, $key, $n);
	 }
	 
	 public function encode($text,$publicKey)
	 {
	 	$str = array();
	 	$str = explode('_', $publicKey);
	 	$pk = $str[0];
	 	$n = $str[1];
	 	$cestr = array();
	 	for($i = 0; $i < strlen($text); $i++)
	 	{
	 		$secretword = $this->colum(substr($text, $i, 1), $pk, $n);
	 		$cestr[$i] = $secretword;
	 	}
	 	return str_replace(array('[',']',' '), array('','',''), $cestr);
	 }
	 
	 public function decode($cestr,$privateKey)
	 {
	 	$str = array();
	 	$destr = "";
	 	$str = explode('_', $privateKey);
	 	$pk = $str[0];
	 	$n = $str[1];
	 	if ($cestr != null)
	 	{
	 		$strs = array();
	 		$strs = explode(',', $cestr);
	 		for ($i = 0; $i < count($strs); $i++){
	 			$word = $this->colum($strs[$i], $pk, $n);
	 			$destr.=chr($word);
	 		}
	 	}
	 	return $destr;
	 }
	 
	 private function makeKey()
	 {
	 	$key = array();
	 	try{
	 		$nm = array();
	 		$nm = $this->inputPQ();
	 		$publicKey = $this->getPublicKey($nm[1]);
	 		$privateKey = $this->getPrivateKey($publicKey, $nm[1]);
	 		$key[0] = $publicKey;
	 		$key[1] = $privateKey;
	 		$key[2] = $nm[0];
	 		$key[3] = $nm[1];
	 	}catch (Exception $e)
	 	{
	 		echo "异常！";
	 	}
	 	return $key;
	 }
	 
	 public function makeRSAKey()
	 {
	 	$t = new RSACoderHelper();
	 	$key2 = array();
	 	$key = array();
	 	$key = $t->makeKey();
	 	$publicKey = $key[0].'_'.$key[2];
	 	$privateKey = $key[1].'_'.$key[2];
	 	$key2[0] = $publicKey;
	 	$key2[1] = $privateKey;
	 	return $key2;
	 }
	 
	 public function randomKeys($length)
	 {
	 	$pattern = '1234567890';
	 	for($i = 0; $i < $length; $i++)
	 	{
	 		$key.= $pattern(mt_rand(0, strlen($pattern)-1));
	 	}
	 	return $key;
	 }
}