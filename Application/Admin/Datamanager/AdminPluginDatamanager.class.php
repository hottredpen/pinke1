<?php
namespace Admin\Datamanager;
class AdminPluginDatamanager{

 	function __construct() {

	}

    public function getData($p=1,$page_size=20,$map=array(),$order){
        $data = $this->_takeFormatData("data",$map,$p,$page_size,$order);
        return $data;
    }

    public function getInfo($id){
        $map['id'] = $id;
        $data = $this->_takeFormatData("data",$map,1,1);
        return $data[0];
    }

    public function getNum($map){
        $data = $this->_takeData("num",$map);
        return $data;
    }

    private function _takeFormatData($type,$map,$p,$page_size,$order){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);
        foreach ($data as $key => $value) {
            $plugins[$value['name']] = $value;
        }
        // 获取插件目录下的所有插件目录
        F('common_local_plugins_local_file',null); // 清空，重新查找
        $dirs = common_local_plugins_local_file();
        foreach ($dirs as $key => $plugin) {
            // 读取未安装的插件
            if (!isset($plugins[$plugin])) {
                $plugins[$plugin]['name'] = $plugin;
                // 获取插件类名
                $class = common_get_plugin_class($plugin);
                // 插件类不存在则跳过实例化
                if (!class_exists($class)) {
                    $plugins[$plugin]['status'] = '-2'; // 插件的入口文件不存在！
                    continue;
                }
                // 实例化插件
                $obj = new $class;
                if (!isset($obj->info) || empty($obj->info)) {
                    $plugins[$plugin]['status'] = '-3';// 插件信息缺失！
                    continue;
                }
                // 插件未安装
                $plugins[$plugin] = $obj->info;
                $plugins[$plugin]['status'] = '-1';
            }
        }
        return $plugins;
    }

    private function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" id desc "){
        $map = array();

        //合并覆盖
        $newmap = array_merge($map, $searchmap);

        $offset = ($p - 1) * $page_size;
        $offset = $offset < 0 ? 0 : $offset;

        if($type=="data"){
            $list = M("admin_plugin")
                    ->field('*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_plugin")
                    ->field('id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}