<?php
namespace Admin\Visitor;

/**
 * 管理员访问者
 * @author hottredpen@126.com
 */
class admin_visitor {

    public  $info     = null;

    public function __construct() {
        if (session('admin')) {
            $this->info = session('admin');
        }
    }

    public function __call($method, $args) {
        if(C('PK_PLUGIN_NAME')){
            $class_name = '\\Plugins\\'.C('PK_PLUGIN_NAME').'\\HandleObject\\'.C('PK_PLUGIN_NAME').'AdminHandleObject';
            return new $class_name($this->info['id']);
        }else{
            $module_name = CONTROLLER_NAME; // 来自模块或者插件的admin
            $class_name = '\\'.$module_name.'\\HandleObject\\'.$module_name.'AdminHandleObject';
            return new $class_name($this->info['id']);
        }
    }

    public function AdminBaseHandleObject(){
        return new \Admin\HandleObject\AdminBaseHandleObject($this->info['id']);
    }

}