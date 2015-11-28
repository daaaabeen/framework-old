<?php
	$soap=new SoapClient('http://localhost:8080/axis2/services/OcrService?wsdl');
//	echo $_GET['imageURI'];
//	echo "<pre>";

	class ocrEnv {
		public $imageURI = "1.png";
	};
	$p = new ocrEnv;
	$uri = $_GET['imageURI'];
	if (strlen($uri) > 20) {
		echo 'error!';
	} else {
		$p->imageURI = $uri;
// 		echo $p->imageURI; 
// 		$params = array($p);
// 		$result=$soap->__soapCall('ocr', $params);
		
		$params = array(array('imageURI'=>'imgtest1000.png'));
		$result = $soap->__soapCall('ocr', $params);
// 		print_r($result);
		print_r($result->return);
	}
?>
