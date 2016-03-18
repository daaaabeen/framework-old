<?php
/**
*  Create On 2010-7-12
*  Author Been
*  QQ:281443751
*  Email:binbin1129@126.com
**/
namespace BF;
include_once('Base.class.php');

class Model  extends \BF\Base {

    protected $_sqlBuider;

    public function getSqlBuilder () {
        if (!$this -> _sqlBuider) {
            require_once('Db/SqlBuilder.php');
            $this -> _sqlBuider = new \BF\Db\SqlBuilder();
        }
        return $this -> _sqlBuider;
    }
    
    /**
     * 删除 delete 的别名
     * @param  SQL  $sql SQL语句
     * @return boolean      成功或者失败
     */
    public function del ($sql = null) {
        return $this -> delete($sql);
    }

    /**
     * 删除
     * @param  SQL  $sql SQL语句
     * @return boolean      成功或者失败
     */
    public function delete ($sql = null) {
        $sql === null && $sql = $this -> getSqlBuilder() -> getSql();
        return $this -> getDb() -> del($sql);

    }

    /**
     * 更新
     * return bool true/false
     */
    public function update ($sql = null) {
        $sql === null && $sql = $this -> getSqlBuilder() -> getSql();
        return $this -> getDb() -> update($sql);
    }
    /**
     * 插入一条数据
     * return id or false
     */
    public function insert ($sql = null) {
        $sql === null && $sql = $this -> getSqlBuilder() -> getSql();
        return $this -> getDb() -> insert($sql);
    }
    /**
     * 选取一条数据
     * return false / array
     */
    public function fetchRow ($sql = null) {
        $sql === null && $sql = $this -> getSqlBuilder() -> getSql();
        return $this -> getDb() -> fetchRow($sql);
    }
    
    /**
     * 返回多条数据
     * return array
     */      
    public function fetchAll ($sql = null) {
        $sql === null && $sql = $this -> getSqlBuilder() -> getSql();
        return $this -> getDb() -> fetchAll($sql);
    }
    
    /**
    * 查询符合某个条件数据在某个表中的条数
    * @param $table $filter
    * @return int
    */
    public function getTotal ($table, $filter=NULL) {
        
        return $this->getDb()->getTotal($table,$filter);
    }

    public function getTotalFromSql($sql){
    
        return $this->getDb()->getTotalFromSql($sql);
    }

    /**
     * 过滤字符串防止被恶意代码攻击
     * @param 要过滤的字符串
     * @return 过滤后的字符串
     */
    public function filter($str){
 
        if (!get_magic_quotes_gpc()) {    // 判断magic_quotes_gpc是否为打开  
            $str = addslashes($str);    // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤  
        }  
        $str = str_replace("_", "\_", $str);    // 把 '_'过滤掉  
        $str = str_replace("%", "\%", $str);    // 把 '%'过滤掉  
        $str = nl2br($str);    // 回车转换  
        $str = htmlspecialchars($str);    // html标记转换  
        return $str;    
    }

    public function keyReplace($arr, $keyMap) {
        $result = array ();
        foreach ($arr as $i => $item) {
            foreach ($keyMap as $k => $v) {
                $result[$i][$v] = $item[$i][$k];
            }
        }
        return $result;
    }
    
    /**
     *
     * @param unknown_type $ts
     * @return string
     */
    public function Timestamp2Datetime($ts){
        return date("Y-m-d H:i:s",$ts);
    }
    
}