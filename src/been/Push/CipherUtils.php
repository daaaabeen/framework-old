<?php
include_once 'security/rsa/RSACoderHelper.php';
include_once 'security/des/DESCoderHelper.php';
class  CipherUtils
{
	public function __construct()
	{
		
	}
	public static function unsymmetryDecrypt($text, $privateKey)
	{
		$rsa = new RSACoderHelper();
		return $rsa->decode($text, $privateKey);
	}
	
	public static function symmetryEncrypt($text,$secretKey)
	{
		$des = new DESCoderHelper();
		return $des->encrypt($text, $secretKey);
	}
}