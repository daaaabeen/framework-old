<?php
class NewsController extends Controller {
	public function __construct() {
		parent::__construct ();
		$this->view->web_host = $this->getRequest ()->hostUrl;
		$this->view->web_app_url = $this->getRequest ()->hostUrl . "/index.php";
	}

	public function Index(){
		$this->getView()->web_action = "news";
		$page = $this->getRequest()->get("page")? $this->getRequest()->get("page") : 1 ;
		$page_size = 10;
		$news = new news();
		$newsList = $news->get_news_list($page,$page_size);
		$this->getView()->news = $newsList;
		$this->getView()->display("index.html");
	}
	
	public function Detail(){
		$this->getView()->web_action = "news";
		$id = intval($this->getRequest()->get("id"));
		$news = new news();
		$detail = $news->get_news_from_id( $id );
		//print_r($detail);
		$this->getView()->detail = $detail;
		$this->getView()->display("detail.html");
	}
	
	
}