<?php
class UploadController extends Controller{
	public function __construct(){
		parent::__construct();
		$this->view->web_host = $this->getRequest()->hostUrl;
		$this->view->web_app_url = $this->getRequest()->hostUrl."/howdomanage.php";
	}
	
	public function Upload(){
		if (!empty($_FILES)) {
			//print_r($_FILES);
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = "common/upload/images/";
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$adder = 'img';
			if($fileType = $this->getRequest()->get("filetype")){
				if(strtolower($fileType) == "file"){
					$targetPath = "common/upload/files/";
					$fileTypes = array('txt','rar','zip','doc','docx','ppt','pptx','pdf','xls','xlsx'); // File extensions
					$adder = 'file';
				}
				if((strtolower($fileType) == "apk"))
				{
					$targetPath = "common/upload/files/";
					$fileTypes = array('zip','rar','apk');
					$adder = 'apk';
				}
			}
				
			//echo $_FILES['Filedata']['name'];
			$fileParts = pathinfo( $_FILES['Filedata']['name'] );
			$newfilename = strtolower($this->getRequest()->cName)."/".$adder.time().".".$fileParts["extension"];
			//print_r(pathinfo( $_FILES['Filedata']['name'] ));
			//echo $newfilename;
			//exit();
			$targetFile = rtrim($targetPath,'/') . '/' . $newfilename;
			//echo $targetFile;
			// Validate the file type
				
	
			if (in_array(strtolower($fileParts['extension']),$fileTypes)) {
				$this->createDir(dirname($targetFile));
				move_uploaded_file($tempFile,$targetFile);
				@chmod($targetFile, 755);
				if($adder == "img"){
					$pic = new picture();
					$id = $pic->addPic($fileParts['extension'],$newfilename);
				}else{
					$file = new file();
					$id = $file->addfile($newfilename, $fileParts['extension'] );
				}
	
				$re['result'] = $id ? $id : 0;
				$re['name'] = $_FILES['Filedata']['name'];
				$re['size'] = $_FILES['Filedata']['size'];//kb
				if($re['size']>1024){
					$re['size'] /= 1024;
					if($re['size']>1024){
						$re['size'] = sprintf("%.2f", ($re['size'] / 1024));
						$re['size'] .= "M";
					}else{
						$re['size'] = sprintf("%.2f", $re['size']);
						$re['size'] .= "KB";
					}
				}else{
					$re['size'] .= "B";
				}
				$re['msg'] = $this->getRequest()->hostUrl."/".$targetPath.$newfilename;
			} else {
				$re['result'] = 0;
				$re['msg'] ='Invalid file type【'+$fileTypes+'】.';
			}
			$jsonstr = json_encode($re);
		}else{
			$re['result'] = 0;
			$re['msg'] ='no file.';
			$jsonstr = json_encode($re);
		}
		echo $jsonstr;
	
	}
	
	protected function createDir($path){
		if (!file_exists($path)){
			$this->createDir(dirname($path));
			mkdir($path, 0777);
		}
	}
}