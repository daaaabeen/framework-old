<?php
class PictureController extends Controller{
	public function __construct(){
	
		parent::__construct();
		//print_r($this->getRequest());
		//$this->view->web_url=$this->getRequest()->hostUrl;
		$this->view->web_host = $this->getRequest()->hostUrl;
		$this->view->web_app_url = $this->getRequest()->hostUrl."/zcmanage.php";
	}
	
	public function picturemanage(){
		$picture = new picture();
		
		if( $do = $this->getRequest()->get('do')){
			$id = $this->getRequest()->get('id');
			if($do == 'del'){
				$delpic = $picture->delfrontpic($id);
				if($delpic){
					$this->view->result = $this->_lang->shanchuchenggong;
				}
				else{
					$this->view->result = $this->_lang->shanchushibai;
				}
			}
			elseif($do == 'enable'){
				$picture->enablepic($id);
			}
			elseif($do == 'disable'){
				$picture->disablepic($id);
			}
		}
		$piclist = $picture->getfrontpic();
		$this->view->piclist = $piclist;
		//print_r($piclist);
		echo $this->view->render("picturemanage.html");
	}
	
	public function addpic(){
		if($_POST){
			$picture = new picture();
			$pic = $picture->frontpic($_POST['picid'], $_POST['url']);
			if($pic){
				$this->view->result = $this->_lang->shangchuanchenggong;
			}else{
				$this->view->result = $this->_lang->shangchuanshibai;
			}
		}
		echo $this->view->render("addpic.html");
	}
	
}