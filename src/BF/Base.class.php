<?php
/**
 * @Author: beenlee
 * @Date:   2016-03-06 00:37:56
 * @Last Modified by:   beenlee
 * @Last Modified time: 2016-03-18 16:26:35
 */
namespace BF;

abstract class Base {
    
    /**
     * App 对象
     * @var App对象
     */
    protected $_app = null;

    /**
     * 获取应用
     * @return App
     */
    public function getApp() {
        
        if (null !== $this -> _app) {
            return $this -> _app;
        }
        if (class_exists('BF\App')) {
            $this -> _app = \BF\App::getInstance();
            return $this -> _app;
        }
        else {
            die ("没有App.class.php 这个文件");
        }
    }
    
    public function  getRequest (){
        return $this -> getApp() -> getRequest();   
    }
    
    public function getDb () {
        return $this -> getApp() -> getDb();
    }
    
    public function getView () {
        return $this -> getApp() ->getView();
    }

    /**
     * 获取内存中缓存的数据
     * @param  string $ns  命名空间
     * @param  string $key 变量名
     * @return mix      缓存的变量
     */
    public function getData ($ns, $key) {
        return $this -> getApp() -> getData($ns, $key);
    }
}
