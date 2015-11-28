<?php
class ActivityController extends Controller{
	
	public function __construct(){
	
		parent::__construct();
		//print_r($this->getRequest());
		//$this->view->web_url=$this->getRequest()->hostUrl;
		$this->view->web_host = $this->getRequest()->hostUrl;
		$this->view->web_app_url = $this->getRequest()->hostUrl."/zcmanage.php";
	}
	
	public function activitylist(){
		$do = $this->getRequest()->get("do");
		if( strtolower( $do )  == "like" ){
			$this->Like();
			exit();
		}
		$activity = new activity();
		if($_POST){
			$type = array_key_exists('type', $_POST) ? $_POST['type'] : 0;
			$state = array_key_exists('state', $_POST) ? $_POST['state'] : 0;
			$end = array_key_exists('end', $_POST) ? $_POST['end'] : 0;
			$page = 1;
			//print_r($_POST);
		}
		else{
			$type = $this->getRequest()->get('type') ? $this->getRequest()->get('type') : 0;
			$state = $this->getRequest()->get('state') ? $this->getRequest()->get('state') : 0;
			$end = $this->getRequest()->get('end') ? $this->getRequest()->get('end') : 0;
			$page = $this->getRequest()->get('page') ? $this->getRequest()->get('page') : 1;
		}
		if( $do = $this->getRequest()->get('do')){
			$id = $this->getRequest()->get('id');
			if($do == 'del'){
				$delpic = $activity->delact($id);
				if($delpic){
					$this->view->result = $this->_lang->shanchuchenggong;
				}
				else{
					$this->view->result = $this->_lang->shanchushibai;
				}
			}
			elseif ($do == 'finished'){
				$activity->finishiact($id);
			}
			elseif ($do == 'failed'){
				$activity->failed($id);
			}
		}
		
		$actlist = $activity->getactivitylist($type, $state, $end, $page, 8);
		$this->view->type = $type;
		$this->view->state = $state;
		$this->view->end = $end;
		$this->view->actlist = $actlist;
		
		//print_r($actlist);
		echo $this->view->render("activitylist.html");
	}
	
	public function actdetail(){
		$id = $this->getRequest()->get('id');
		$activity = new activity();
		if( $do = $this->getRequest()->get('do')){
			//$id = $this->getRequest()->get('id');
			if($do == 'appr'){
				$activity->approve($id);
				$this->view->result = $this->_lang->shenhetongguo;
			}
			elseif ($do == 'reje'){
				$activity->reject($id);
				$this->view->result = $this->_lang->shenhebutongguo;
			}
		}
		$detail = $activity->actdetail($id);
		$piclist = $activity->getactpic($id);
		if($piclist){
			$this->view->pic = $piclist;
		}
		$this->view->detail = $detail;
		//print_r($detail);
		echo $this->view->render("actdetail.html");
	}
	
	public function actfeeling(){
		$id = $this->getRequest()->get('id');
		$activity = new activity();
		if( $do = $this->getRequest()->get('do')){
			//$id = $this->getRequest()->get('id');
			if($do == 'appr'){
				$activity->appr($id);
				$this->view->result = $this->_lang->shenhetongguo;
			}
			elseif ($do == 'reje'){
				$activity->reje($id);
				$this->view->result = $this->_lang->shenhebutongguo;
			}
		}
		$detail = $activity->actdetail($id);
		$piclist = $activity->getactpic($id);
		//print_r($piclist);
		$this->view->detail = $detail;
		$this->view->pic = $piclist;
		echo $this->view->render('actfeeling.html');
	}
	
	/**
	 * 评论
	 * Enter description here ...
	 */
	public function Comment(){
		$id = intval($this->getRequest()->get("id"));
		$do = $this->getRequest()->get("do");
		
	
		
		$comment = new Comment();
		
		if($do == "del"){
			$cid = intval( $this->getRequest()->get("cid") );
			if( $comment->del_comment($cid) ){
				$this->getView()->result = "删除成功！";
			}else{
				$this->getView()->result = "删除失败！";
			}
		}
		
		$commentList = $comment->getAllComment($id);
		//print_r($commentList);
		$act = new activity();
		$detail = $act->actdetail($id);
		$this->getView()->actDetail=$detail;
		$this->getView()->commentList = $commentList;
		$this->getView()->display("comment.html");
	}
	
/**
	 *  赞
	 * Enter description here ...
	 */
	protected  function Like(){
		$id = $this->getRequest()->get("id");
		$act = new activity();
		$code = $act->add_like( $id );
		//echo $code;
		if($code > 1){
			$this->getView()->setState('1');
			$this->getView()->setMsg("succeed!");
			$this->getView()->setData($code);
		}else{
			$this->getView()->setMsg("失败！");
		}
		$this->getView()->display("json");
		
	}
	
}