<?php
namespace Admin\Datamanager;
use Common\Datamanager\BaseDatamanager;
// todo 待优化
class AdminPluginDatamanager extends BaseDatamanager{

    protected $replaceOptions = array(
        'id'            => 'ap.id'
    );

    protected function _takeFormatData($type,$map,$p,$page_size,$order){
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

    protected function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" id desc "){
        $searchmap = $this->replaceMap($searchmap);
        $order     = $this->replaceOrder($order);
        $offset    = $this->getOffset($p,$page_size);        

        $map = array();

        //合并覆盖
        if(count($searchmap) > 0){
            $newmap = array_merge($map, $searchmap);
        }else{
            $newmap = array();
        }

        if($type=="data"){
            $list = M("admin_plugin as ap")
                    ->field('ap.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_plugin as ap")
                    ->field('ap.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}