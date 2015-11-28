<?php
class DESCoderHelper 
{
	var $modes = MCRYPT_MODE_ECB;
	var $cipher = MCRYPT_DES;
	public function encrypt($input,$mKey)
	{
		 $size = mcrypt_get_block_size($this->cipher, $this->modes);
         $input = $this->pkcs5_pad($input, $size);
         $key = $mKey;
         //echo $key;
         $td = mcrypt_module_open($this->cipher, '', $this->modes, '');
         $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
         @mcrypt_generic_init($td, $key, $iv);
         $data = mcrypt_generic($td, $input);
         $data = base64_encode($data);
         mcrypt_generic_deinit($td);
         mcrypt_module_close($td);
         return $data;	
	}
	public  function decrypt($encrypted,$mKey)
    {
         $encrypted = base64_decode($encrypted);
         $key =$mKey;
         $td = mcrypt_module_open($this->cipher,'',$this->modes,'');
         //使用MCRYPT_DES算法,cbc模式
         $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
         @mcrypt_generic_init($td, $key, $iv);
         //初始处理
         $decrypted = mdecrypt_generic($td, $encrypted);
         //解密
         mcrypt_generic_deinit($td);
         //结束
         mcrypt_module_close($td);
         $y=$this->pkcs5_unpad($decrypted);
         return $y;
      }
      public function pkcs5_pad ($text, $blocksize)
      {
         $pad = $blocksize - (strlen($text) % $blocksize);
         return $text . str_repeat(chr($pad), $pad);
      }
      public function pkcs5_unpad($text)
      {
         $pad = ord($text{strlen($text)-1});
         if ($pad > strlen($text))
         {
             return false;
         }
         if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
         {
            return false;
         }
         return substr($text, 0, -1 * $pad);
      }
}