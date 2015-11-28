<?php
class NewsController extends Controller{
	
	public function __construct(){
	
		parent::__construct();
		//print_r($this->getRequest());
		//$this->view->web_url=$this->getRequest()->hostUrl;
		$this->view->web_host = $this->getRequest()->hostUrl;
		$this->view->web_app_url = $this->getRequest()->hostUrl."/zcmanage.php";
	
	}
	
	public function getallnews(){
		$news = new news();
		$page = $this->getRequest()->get('page') ? $this->getRequest()->get('page') : 1;
		if($do = $this->getRequest()->get('do')){
			$id = $this->getRequest()->get('id');
			if($do == 'del'){
				$delnews = $news->delnews($id);
				if($delnews){
					$this->view->result = $this->_lang->shanchuchenggong;
				}
				else{
					$this->view->result = $this->_lang->shanchushibai;
				}
			}
		}
		$newslist = $news->get_news_list($page, 8);
		//print_r($newslist);
		$this->view->newslist = $newslist;
		echo $this->view->render('allnews.html');
	}
	
	public function addnews(){
		$news = new news();
		$userinfo = $this->getData("userinfo");
		if($_POST){
			$add = $news->addnews($_POST['name'], $_POST['fileid'], $_POST['content'], $userinfo['admin_id']);
			if($add){
				$this->view->result = $this->_lang->tianjiachenggong;
			}
			else{
				$this->view->result = $this->_lang->tianjiashibai;
			}
		}
		
		echo $this->view->render('addnews.html');
	}
	
	
	public function editnews(){
		$id = $this->getRequest()->get('id'); 
		$news = new news();
		
		if($_POST){
			$edit = $news->editnews($id, $_POST['name'], $_POST['content'],$_POST['fileid']);
			if($edit){
				$this->view->result = $this->_lang->xiugaichenggong;
			}
			else{
				$this->view->result = $this->_lang->xiugaishibai;
			}
		}
		$newsdetail = $news->get_news_from_id($id);
		$this->view->detail = $newsdetail;
		echo $this->view->render('editnews.html');
	}
}