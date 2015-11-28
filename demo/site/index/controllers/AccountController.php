<?php
class AccountController extends Controller {
	public function __construct() {
		parent::__construct ();
		$this->view->web_host = $this->getRequest ()->hostUrl;
		$this->view->web_app_url = $this->getRequest ()->hostUrl . "/index.php";
	}
	
	/**
	 * 登陆
	 */
	public function Login() {
		
		
		$renrenConf = $this->getApp()->loadConf("renren");
		$r_url = $this->getRequest()->get("redirect_uri");
		if($r_url){
			$session = $this->getApp()->loadUtilClass("SessionUtil");
			//print_r($this->getApp());
			$session->set( "sess_redirect_uri", $r_url );
		}
		if(!isset($_REQUEST['code'])){
			$url = $renrenConf["loginUrl"]."?client_id=".$renrenConf["APIKey"].
			"&response_type=code&scope=".$renrenConf["scope"].
			"&redirect_uri=".$this->view->web_app_url."/Account/Login";
			echo "<meta http-equiv=refresh content='0; url=".$url."' >";
		}else{
			$code = $_REQUEST['code'];
			// 发起获取 access token请求
			$url = "http://graph.renren.com/oauth/token?client_id=".$renrenConf['APIKey']."&code=$code".
			"&grant_type=authorization_code&client_secret=".$renrenConf['SecretKey'].
			"&redirect_uri=".$this->view->web_app_url."/Account/Login";
			$curl = $this->getApp()->loadUtilClass("CurlUtil");
			$result = $curl->get($url);
			
			//print_r($result);
			//exit();
			if($result["state"]){
				
				$de_json = json_decode($result["info"]);
			
				if( property_exists($de_json,"user") ){
					$renrenInfo = $de_json->user;
					//print_r($renrenInfo);
					//exit();
					$user = new user();
					if( $userinfo = $user->registerUser( $renrenInfo )){
						$session = $this->getApp()->loadUtilClass("SessionUtil");
						//print_r($this->getApp());
						$session->set( "session_index_userid", $userinfo["id"] );
						$session->set( "session_index_token",  $de_json->access_token );
						if( $url = $session->get( "sess_redirect_uri") ) {
							echo "<meta http-equiv=refresh content='0; url=".$url."' >";
						}else{
							$this->getApp()->gotoUrl("index","index");
						}
						
					} else{
						echo "绑定人人账号失败！";
					}
				}else{
					echo "授权人人账号的code错误！";
				}
				
			
				
			
			}else{
				//
				echo "登陆失败！";
			}	
		}
	
	}
	
	/**
	 * 注销
	 */
	public function Logout() {
		
		$session = $this->getApp()->loadUtilClass("SessionUtil");
		$session->clear();
		$this->gotoUrl("index","index");
	}
	
	
	/**
	 * 自己的私有信息
	 * Enter description here ...
	 */
	public function Myinfo(){
	
	}
	
}