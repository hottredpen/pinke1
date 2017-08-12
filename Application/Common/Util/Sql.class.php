<?php
namespace Common\Util;
/**
 * Sql语句处理执行类
 */
class Sql {

    public $origin_prefix;  //  

    /**
     * 解析数据库语句函数
     * @param string $sql  sql语句   带默认前缀的
     * @param string $tablepre  自己的前缀
     * @return multitype:string 返回最终需要的sql语句
     */
     static public function sql_split($sql, $tablepre,$origin_prefix) {
        if ($tablepre != $origin_prefix && $origin_prefix != "") {
            $sql = str_replace($origin_prefix, $tablepre, $sql);
        }
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);
        if ($r_tablepre != $s_tablepre) {
            $sql = str_replace($s_tablepre, $r_tablepre, $sql);
        }
        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if($str1 != '#' && $str1 != '-'){
                    $ret[$num] .= $query;
                }
            }
            $num++;
        }
        return $ret;
    }

    /**
     * 执行文件中SQL语句函数
     * @param string $file sql语句文件路径
     * @param string $origin_prefix  原来的前缀
     * @return multitype:string 返回最终需要的sql语句
     */
    public function execute_sql_from_file($file,$origin_prefix = "") {
        $sql_data = file_get_contents($file);
        if (!$sql_data) {
            return true;
        }
        $sql_format = $this->sql_split($sql_data, C('DB_PREFIX') ,$origin_prefix);
        $counts = count($sql_format);
        for ($i = 0; $i < $counts; $i++) {
            $sql = trim($sql_format[$i]);
            D()->execute($sql);
        }
        return true;
    }
}
