<?php
namespace Admin\Datamanager;
use Common\Datamanager\BaseDatamanager;
// todo 待优化
class AdminModuleDatamanager extends BaseDatamanager{

    protected $replaceOptions = array(
        'id'            => 'am.id'
    );

    // todo 不常用的不写入datamanger里面
    public function getSettingTabByGroup($group="common"){
        $modules = $this->getData();
        array_unshift($modules,array('title'=>'通用','href'=>U('Admin/admin/setting',array('group'=>'common'))));
        
        $tab_list      = array();
        $cur_group_key = 0;
        $module_index  = 0;
        foreach ($modules as $key => $value) {
            array_push($tab_list, array('title'=>$value['title'],'href'=>U('Admin/admin/setting',array('group'=>strtolower($value['name'])))));
            if($group == strtolower($value['name']) ){
                $cur_group_key = $module_index;
            }
            $module_index++;
        }
        return array('data'=>$tab_list,'key'=>$cur_group_key);
    }



    protected function _takeFormatData($type,$map,$p,$page_size,$order){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);

        foreach ($data as $key => $value) {
            $modules[$value['name']] = $value;
        }
        // 获取模块目录下的所有模块目录
        F('common_local_modules_local_file',null); // 清空，重新查找
        $dirs = common_local_modules_local_file();

        foreach ($dirs as $key => $module) {
            // 读取未安装的模块
            if (!isset($modules[$module])) {
                $modules[$module]['name'] = $module;
                // 获取模块类名
                $class = common_get_module_class($module);
                // 模块类不存在则跳过实例化
                if (!class_exists($class)) {
                    $modules[$module]['status'] = '-2'; // 模块的入口文件不存在！
                    continue;
                }
                // 实例化模块
                $obj = new $class;
                if (!isset($obj->info) || empty($obj->info)) {
                    $modules[$module]['status'] = '-3';// 模块信息缺失！
                    continue;
                }
                // 模块未安装
                $modules[$module]           = $obj->info; // 未安装的模块信息
                $modules[$module]['status'] = '-1';
            }
        }
        // dump($modules);
        // exit();
        return $modules;
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
            $list = M("admin_module as am")
                    ->field('am.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_module as am")
                    ->field('am.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}