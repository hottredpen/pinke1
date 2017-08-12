<?php
namespace Common\Behavior;
/**
 * 注册钩子
 */
class HookBehavior {

    public function run(&$params){
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;

        $hook_plugins = S('hook_plugins');
        $hooks        = S('hooks');
        $plugins      = S('plugins');

        if (!$hook_plugins) {
            // 所有钩子
            $hooks = M('admin_hook')->where(array('status'=>1))->getField('name,status');
            // 所有插件
            $plugins = M('admin_plugin')->where(array('status'=>1))->getField('name,status');
            // 钩子对应的插件
            $hook_plugins = M('admin_hook_plugin')->where(array('status'=>1))->order('hook,sort')->select();
            // 非开发模式，缓存数据
            if (APP_DEBUG == false) {
                S('hook_plugins', $hook_plugins);
                S('hooks', $hooks);
                S('plugins', $plugins);
            }
        }
        if ($hook_plugins) {
            $local_pulgins =  common_local_plugins_local_file();
            foreach ($hook_plugins as $key => $value) {
                if(!in_array($value['plugin'],$local_pulgins) ){
                    continue; // 本地不存在的插件，可能数据库里删留有之前的
                }
                if (isset($hooks[$value['hook']]) && isset($plugins[$value['plugin']])) {
                    \think\Hook::add($value['hook'], common_get_plugin_class($value['plugin']));
                }
            }
        }
    }
}
