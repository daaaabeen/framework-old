<?php
/**
*  Create On 2010-7-12
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/
namespace BF;
include_once 'Controller.class.php';
include_once 'Model.class.php';
include_once 'Request.class.php';
include_once 'MiddleWare.class.php';

class App{
    protected static $_instance = null;
    protected $_request;
    protected $_cPath;
    protected $_mPath;
    protected $_view;
    protected $_db;

    protected $_data = array();//用于存储一些自定义的数据在内存中
    protected $_utils = array();//用于存储加载过的工具类
    protected $_models = array();//用于存储加载过的model类
    
    protected $_confPath = "./";
    protected $_confs = array();//用于存储加载过的配置项
    
    // 中间件列表
    protected $_MWList = array();
    
    protected function __construct(){
        $this -> _autoload();
        $this -> loadUtilClass('SessionUtil') -> start();
        $this -> _request = new Request();
    }
    
    /**
     * 获取实例 
     * @return APP
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function run( $conf = array('cPath' => "./controllers", 'mPath' => './model')) {
        if (!$this->_cPath) $this -> setCPath($conf['cPath']);
        if (!$this->_mPath) $this -> setMPath($conf['mPath']);
        $this -> preDispatch();
        $this -> dispatch();
        return true;
    }

    /**
     * 设置controller路径
     * @param string $path
     * @return APP
     */
    public function setCPath ($path) {
        $this -> _cPath = $path;
    }
    
    /**
     * 设置model路径
     * @param string $path
     * @return APP
     */
    public function setMPath ($path) {
        $this -> _mPath = $path;
    }

    /**
     * 获取request数据
     * */
    public function getRequest () {
        return $this->_request;
    }
    
    
    public function  dispatch () {
        //判断当前有没有这个类和方法
        $cName = $this -> _request -> cName . 'Controller';
        $aName = $this -> _request -> aName;
        if (!$this -> runCA($cName, $aName)) {
            $this -> error404();
        }
    }
    
    /**
     * 分发之前
     * 可以在这里对用户的访问进行控制
     * @return boolean
     */
    public function preDispatch () {
        // 执行注入的中间件
        $this -> excuteMiddelWare();
        return true;
    }
    
    /**
     * 执行控制器的方法
     * @param string $cName
     * @param string $aName
     * @return controller
     */
    public function runCA ($cName, $aName, $param = null) {
        if (class_exists($cName)) {
            $controller = new $cName();
            if (method_exists($controller, $aName)) {
                if ($param !== null) {
                    $controller -> $aName($param);
                }
                else {
                    $controller -> $aName();
                }
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param unknown_type $Viewarr
     * @return string
     */
    public function setView (View $view) {
        $this -> _view = $view;
        return $this;
    }
    
    /**
     * 
     * @return View
     */
    public function getView () {
        return $this -> _view;
    }
    
    public function setDb ($db) {
        $this -> _db = $db;
        return $this;
    }
    
    public function getDb () {
        return $this -> _db;        
    }
    
    /**
     * 注册中间件
     * @param  MiddleWare $mw [description]
     * @return [type]         [description]
     */
    public function registMiddleWare(MiddleWare $mw) {
        $this -> _MWList[] = $mw;
        return $this;
    }

    // 依次执行中间件
    protected function excuteMiddelWare() {
        array_map(function ($mw){
            $mw -> excute();
        }, $this -> _MWList);
    }
    
    /**
     * 添加缓存数据
     * @param unknown_type $key
     * @param unknown_type $value
     */
    public function putData ($namespace, $key, $value) {
        if (!isset($this -> _data[$namespace])) {
            $this -> _data[$namespace] = array();
        }
        $this -> _data[$namespace][$key] = $value;
        return $this;
    }
    
    /**
     * 通过key获取缓存的数据
     * @param unknown_type $key
     * @return multitype:
     */
    public function getData ($namespace, $key = null) {
        if (isset($this->_data[$namespace])) {
            if ($key !== null) {
                return isset($this->_data[$namespace][$key]) ? $this->_data[$namespace][$key] : null;
            }
            else {
                return $this->_data[$namespace];
            }
        }

        return null;
    }

    /**
     * 通过key获取数据
     * @param unknown_type $key
     * @return multitype:
     */
    public function error404 ($msg = null){
        if (!$this -> runCA('ErrorController', 'Error404', $msg)) {
            header("HTTP/1.1 404 Not Found");  
            header("Status: 404 Not Found");  
        }
        exit;
    }

    /**
     * 控制页面跳转
     * @param  $cName 控制器名
     * @param  $aName 方法名
     * @param  $time  停留时间
     * @param  $message 显示信息
     * @return 空
     */
    public function gotoUrl($url, $time = 0, $message = "") {
        if($time > 0){
            echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
            echo $message . "<br/>" . $time . "秒后自动跳转！";
        }
        echo "<meta http-equiv=refresh content='$time; url=" . $url . "' >";
        exit();
    }

    
    /**
     * 加载工具类
     * @param  $classname
     * @return  @param
     */
    public function loadUtilClass($classname){
        if (array_key_exists($classname, $this->_utils)) {
            return $this->_utils[$classname];
        }
        $new_filename = 'Util/' . $classname . '.php';
        
        require_once($new_filename); // 载入文件
        
        // if (class_exists($classname)) {
        //     $this -> _utils[$classname] =  new $classname();
        //     return $this -> _utils[$classname];
        // }
        // else{
        //     return false;
        // }
        return $this -> _utils[$classname] =  new $classname();
    }
    
    /**
     * 设置配置文件的路径末尾不加 /
     * @param unknown_type $path
     * @return App
     */
    public function setConfPath ($path) {
        $this -> _confPath = rtrim($path, "/");
        return $this;
    }
    
    /**
     * 加载配置文件
     * @param  string $confName 配置项名字
     * @return mix           配置信息
     */
    public function loadConf ($confName) {

        if (array_key_exists($confName, $this -> _confs)) {
            return $this->_confs[$confName];        
        }
        // 载入文件
        return  $this->_confs[$confName] = require_once($this -> _confPath . '/' . $confName . '.php');
    }

    public function loadModel ($modelName) {
        if (array_key_exists($modelName, $this->_models)) {
            return $this->_models[$modelName];
        }
        return $this->_models[$modelName] = new $modelName();
    }

    private function _autoload() {
        spl_autoload_register (function ($classname) {
            if (strpos($classname, 'Controller')) {             
                file_exists($this -> _cPath . '/' . $classname . '.php')
                    && require_once ($this -> _cPath . '/' . $classname . '.php');
            }
            else {
                file_exists($this -> _mPath . '/' . $classname . '.php')
                    && require_once ($this -> _mPath . '/' . $classname . '.php');
            }
        });
    }
    

}