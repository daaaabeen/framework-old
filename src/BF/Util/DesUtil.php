<?php
class DesUtil {  
	//private static byte[] iv = {1,2,3,4,5,6,7,8};  
	//private static $iv = array('o','p','e','n','f','i','r','e');  
	private  $iv = 'openfire';
    private  $server = "ifeng";
    
    public function encryptDES($encryptString, $username){   
        $encryptKey = $this->getKey($username);
		$size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $str = $this->pkcs5Pad($encryptString, $size);
        return base64_encode( mcrypt_cbc(MCRYPT_DES, $encryptKey, $str, MCRYPT_ENCRYPT, $this->iv) ); 
		
    }  
    public function decryptDES( $decryptString, $username){  
		$encryptKey = $this->getKey($username);
        $strBin = base64_decode($decryptString);
        $str = mcrypt_cbc(MCRYPT_DES, $encryptKey, $strBin, MCRYPT_DECRYPT, $this->iv);
        $str = $this->pkcs5Unpad($str);
        return $str;

    }  
    protected function pkcs5Unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }

    protected function pkcs5Pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private function getKey($username)
    {
    	//String key;
    	//String temp;
    	$temp = $username.$this->server;
    	$key = base64_encode($temp);
    	//返回前8位作为key
    	return substr($key,0, 8);
    }
}
/**使用方法
$des = new DES();
$word = "admin";
$enstr = $des -> encryptDES('admin','admin');//加密
echo $enstr."-------";
echo $des ->decryptDES($enstr,'admin');//解密
*/

?>