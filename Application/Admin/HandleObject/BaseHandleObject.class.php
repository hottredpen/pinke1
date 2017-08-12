<?php
namespace Admin\HandleObject;
/**
 * AdminHandleObject
 * 管理员操作对象
 */
class BaseHandleObject {
	protected $uid;

    public function __construct($uid) {
    	if((int)$uid>0){
    		$this->uid = $uid;
    	}
    }
    /**
     * 做登录或者权限检测
     * 对外方法public 改成private 就可以进行权限检测
     * 
     */
    public function __call($method, $args) {
        if((int)$this->uid == 0){
            return array('error'=>1,'info'=>'请登录之后操作');
        }
        // 检查是否存在方法$method
        if (method_exists($this, $method)) {
            $before_method = 'before_' + $method;
            // 检查是否存在方法$before_method
            if (method_exists($this, $before_method)) {
                // 调用$before_method，检查其返回值，决定是否跳过函数执行
                if (call_user_func_array(array($this, $before_method), $args)) {
                    return call_user_func_array(array($this, $method), $args);
                }
            } else {
                // $before_method不存在，直接执行函数
                return call_user_func_array(array($this, $method), $args);
            }
        } else {
            // 不存在时，检测是否是add、save、delete+模型名称的类型
            if(C('CPK_FROM_MODULE_ADMIN')){
                $module_name = CONTROLLER_NAME;
                include_once APP_PATH. $module_name .'/Common/function.php';
            }else{
                $module_name = "Admin";
            }
            if(defined('PK_PLUGIN_NAME')){
                $file = './Application/Plugins/'.PK_PLUGIN_NAME.'/Datamanager/AutoHandleDatamanager.class.php';
                if(!is_file($file)){
                    return array('error'=>1,'info'=>'未在插件'.PK_PLUGIN_NAME.'分组下的AutoHandleDatamanager.class.php中找到该方法');
                }
                $thisConfig = D("Plugins://".PK_PLUGIN_NAME.'/AutoHandle','Datamanager')->getConfigData($method);
                if($thisConfig){
                    $module_name = common_replace_plugin_name_to_module_name(PK_PLUGIN_NAME);
                    include_once APP_PATH. $module_name .'/Common/function.php';
                    include_once APP_PATH."Plugins/". PK_PLUGIN_NAME .'/Common/function.php';
                    $is_plugins = true;
                    return $this->_action_do_return(PK_PLUGIN_NAME,$thisConfig,$args,$is_plugins);
                }
            }else{
                $file = './Application/'.$module_name.'/Datamanager/AutoHandleDatamanager.class.php';
                if(!is_file($file)){
                    return array('error'=>1,'info'=>'未在'.$module_name.'分组下的AutoHandleDatamanager.class.php中找到该方法');
                }
                $thisConfig = D($module_name.'/AutoHandle','Datamanager')->getConfigData($method);
                if($thisConfig){
                    return $this->_action_do_return($module_name,$thisConfig,$args);
                }
            }

        }
        return array('error'=>1,'info'=>'未在'.$module_name.'分组下的Admin'.$module_name.'HandleObject找到'.$method.'方法，且在AutoHandleDatamanager中也未找到该方法'.$method);
    }


    private function _action_do_return($module_name,$thisConfig,$args,$is_plugins=false){

        // 如果是插件，就在前加 Plugins://
        $source_pre = "";

        if($is_plugins){
            $source_pre = "Plugins://";
        }

        switch ($thisConfig['action']) {
            case 'add':
                $Model = D($source_pre.$module_name.'/'.$thisConfig['model']);
                if (!$Model->field($thisConfig['field'])->create($_POST,$thisConfig['key'])){
                    return array("error"=>1,"info"=>$Model->getError());
                }
                $res = $Model->add();
                if($res){
                    return array("error"=>0,"info"=>"添加".$thisConfig['name']."成功","id"=>$res);
                }else{
                    return array("error"=>1,"info"=>"添加".$thisConfig['name']."失败");
                }
                break;
            case 'create':
                $Model = D($source_pre.$module_name.'/'.$thisConfig['model']);
                if (!$Model->field($thisConfig['field'])->create($_POST,$thisConfig['key'])){
                    return array("error"=>1,"info"=>$Model->getError());
                }
                $res = $Model->add();
                if($res){
                    return array("error"=>0,"info"=>"添加".$thisConfig['name']."成功","id"=>$res);
                }else{
                    return array("error"=>1,"info"=>"添加".$thisConfig['name']."失败");
                }
                break;
            case 'save':
                if($_POST['id'] == 0){
                    return array("error"=>1,"info"=>"此方法非以post方法及id作为参数，无法试用通用操作，请手动创建该方法");
                }
                $Model = D($source_pre.$module_name.'/'.$thisConfig['model']);
                if (!$Model->field($thisConfig['field'])->create($_POST,$thisConfig['key'])){
                    return array("error"=>1,"info"=>$Model->getError());
                }
                $res = $Model->where(array('id'=>(int)$_POST['id']))->save();
                if($res){
                    return array("error"=>0,"info"=>"修改".$thisConfig['name']."成功","id"=>$_POST['id']);
                }else{
                    return array("error"=>1,"info"=>"修改".$thisConfig['name']."失败");
                }
                break;
            case 'ajax':
                if($_POST['id'] == 0){
                    return array("error"=>1,"info"=>"此方法非以post方法及id作为参数，无法试用通用操作，请手动创建该方法");
                }
                $Model = D($source_pre.$module_name.'/'.$thisConfig['model']);
                if(!in_array($_POST['field'],  explode(",", $thisConfig['field']) )){
                    return array("error"=>1,"info"=>'该字段不能ajax编辑');
                }
                $res = $Model->where(array('id'=>(int)$_POST['id']))->setField($_POST['field'],$_POST['val']);
                if($res){
                    return array("error"=>0,"info"=>"ajax编辑成功","id"=>$_POST['id']);
                }else{
                    return array("error"=>1,"info"=>"ajax编辑失败");
                }
                break;
            case 'delete':
                if($_POST['id'] == 0){
                    return array("error"=>1,"info"=>"此方法非以post方法及id作为参数，无法试用通用操作，请手动创建该方法");
                }
                $Model = D($source_pre.$module_name.'/'.$thisConfig['model']);
                if (!$Model->field($thisConfig['field'])->create($_POST,$thisConfig['key'])){
                    return array("error"=>1,"info"=>$Model->getError());
                }
                $res = $Model->where(array('id'=>(int)$_POST['id']))->delete();
                if($res){
                    return array("error"=>0,"info"=>"删除".$thisConfig['name']."成功","id"=>$_POST['id']);
                }else{
                    return array("error"=>1,"info"=>"删除".$thisConfig['name']."失败");
                }
                break;

            case 'batchdelete':

                $Model = D($source_pre.$module_name.'/'.$thisConfig['model']);
                if (!$Model->field($thisConfig['field'])->create($_POST,$thisConfig['key'])){
                    return array("error"=>1,"info"=>$Model->getError());
                }
                $ids_arr = $Model->getBatchIds();
                $res     = $Model->where(array('id'=>array('in',$ids_arr)))->delete();
                if($res){
                    return array("error"=>0,"info"=>"批量删除".$thisConfig['name']."成功","id"=>$_POST['id']);
                }else{
                    return array("error"=>1,"info"=>"批量删除".$thisConfig['name']."失败");
                }
                break;

            default:
                return array("error"=>1,"info"=>"无此操作类型".$thisConfig['action']);
                break;
        }
    }

}