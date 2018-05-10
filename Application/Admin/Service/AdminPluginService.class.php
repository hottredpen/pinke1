<?php
namespace Admin\Service;
use Common\Util\Sql;
class AdminPluginService {
    /**
     * 安装
        ＠todo 以后改安装插件为POST事件，各数据添加都经过model验证 hottredpen@126.com
     　　　１、添加插件
        ２、添加插件相关的钩子（一对多）（可能包含系统的钩子）
        ３、添加钩子库中的本插件所添加钩子（唯一）（插件钩子命名必须已　插件名　开头）
        ４、执行sql
     */
    public function install($plugin_name,$info,$hook_data,$sql_origin_prefix){
        $adminPluginModel = D("Admin/AdminPlugin");
        // admin_plugin

        if (!$adminPluginModel->create($info,11)){
            return array("error"=>1,"info"=>$adminPluginModel->getError());
        }
        $res_add = $adminPluginModel->add();
        if(!$res_add){
            return array('error'=>1,'info'=>'admin_plugin添加失败');
        }


        // admin_hook_plugin
        $all_is_ok = true;
        foreach ($hook_data as $hook => $hook_about) {
            $add[$hook]                = array();
            $add[$hook]['hook']        = $hook;
            $add[$hook]['plugin']      = $plugin_name;
            $add[$hook]['create_time'] = time();
            $add[$hook]['update_time'] = time();
            $res_hook_plugin = M('admin_hook_plugin')->add($add[$hook]);
            if(!$res_hook_plugin){
                $all_is_ok = false;
            }
            // 如果有插件自带的钩子，则添加进admin_hook
            $has = M('admin_hook')->where(array('name'=>$hook))->find();
            if(!$has){
                $add_hook[$hook]                = array();
                $add_hook[$hook]['name']        = $hook;
                $add_hook[$hook]['plugin']      = $plugin_name;
                $add_hook[$hook]['description'] = $hook_about;
                $add_hook[$hook]['system']      = 0;
                $add_hook[$hook]['create_time'] = time();
                $add_hook[$hook]['update_time'] = time();
                $res_hook = M('admin_hook')->add($add_hook[$hook]);

                if(!$res_hook){
                    $all_is_ok = false;
                }
            }
        }
        if(!$all_is_ok){
            return array('error'=>1,'info'=>'钩子添加错误');
        }
        // sql
        $res_sql = $this->_execute_sql($plugin_name,"install",$sql_origin_prefix);
        if($res_sql['error'] == 1){
            return array('error'=>1,'info'=>$res_sql['info']);
        }
        S('hook_plugins', null);
        S('hooks', null);
        S('plugins', null);
        return array('error'=>0,$plugin_name.'安装完成');
    }

    /**
     * 卸载
        １、删除插件相关的钩子（一对多）（可能包含系统的钩子）
        ２、删除钩子库中的本插件所添加钩子（唯一）（插件钩子命名必须已　插件名　开头）
        ３、删除插件的（数据库里的配置）
        ４、执行sql
     */
    public function uninstall($plugin_name,$sql_origin_prefix){
        $adminPluginModel = D("Admin/AdminPlugin");
        // hook_plugin
        $data = M('admin_hook_plugin')->where(array('plugin'=>$plugin_name))->select();
        if(count($data) > 0){
            $res = M('admin_hook_plugin')->where(array('plugin'=>$plugin_name))->delete();
            if(!$res){
                return array('error'=>1,'info'=>'hook_plugin删除失败');
            }
        }

        // hook
        $data_hook = M('admin_hook')->where(array('plugin'=>$plugin_name))->select();
        if(count($data_hook) > 0){
            $res = M('admin_hook')->where(array('plugin'=>$plugin_name))->delete();
            if(!$res){
                return array('error'=>1,'info'=>'hook删除失败');
            }
        }

        // admin_plugin
        $plugin_info = $adminPluginModel->where(array('name'=>$plugin_name))->find();
        if (!$adminPluginModel->create($plugin_info,13)){
            return array("error"=>1,"info"=>$adminPluginModel->getError());
        }
        $res_del = $adminPluginModel->where(array('id'=>$plugin_info['id']))->delete();
        if(!$res_del){
            return array('error'=>1,'info'=>$plugin_name.'卸载失败');
        }

        // sql
        $res_sql = $this->_execute_sql($plugin_name,"uninstall",$sql_origin_prefix);
        if($res_sql['error'] == 1){
            return array('error'=>1,'info'=>$res_sql['info']);
        }
        S('hook_plugins', null);
        S('hooks', null);
        S('plugins', null);
        return array('error'=>0,'卸载完成');
    }

    private function _execute_sql($plugin_name,$sql_file_name,$sql_origin_prefix){

        $sql_file = realpath(C('plugin_path').$plugin_name.'/'.$sql_file_name.'.sql');

        if (file_exists($sql_file)) {
            $sql_object = new Sql();
            $sql_status = $sql_object->execute_sql_from_file($sql_file,$sql_origin_prefix);
            if (!$sql_status) {
                return array('error'=>1,'info'=>'执行插件SQL卸载语句失败');
            }
        }
        return array('error'=>0,'info'=>'ok');
    }
}