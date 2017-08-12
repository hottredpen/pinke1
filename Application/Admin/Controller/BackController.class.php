<?php
// +----------------------------------------------------------------------
// | 品客PHP框架 [ pinkePHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 浙江蓝酷网络科技有限公司 [ http://www.lankuwangluo.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.pinkephp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Controller\CommonBaseController;

class BackController extends CommonBaseController {

    public    $visitor;
    protected $action_list; // 带有映射动作时，对url进行重新制定跳转

    protected function _initialize() {
        header("Content-Type: text/html; charset=utf-8");
        parent::_initialize();
        if(!in_array(ACTION_NAME, array('verify_code'))){
            $this->_init_visitor();
        }
        //检测权限
        $check_auth = $this->_checkAuth();
    }
    /**
    * 初始化访问者
    */
    private function _init_visitor() {
        $this->visitor = new \Admin\Visitor\admin_visitor();
    }
    /**
     * 查看是否具有权限
     */
    private function _checkAuth(){
        $admin_session = session('admin');
        if ( (!isset($admin_session) || !$admin_session) && !in_array(ACTION_NAME, array('login','verify_code','logout')) ) {
            $this->redirect('index/login');
        }else{

            $current_url    = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME) ;
            $res_check_auth = D('Admin/AdminMenu','Datamanager')->getAuthDataByUrl($current_url);

            $admin_top_menu_name = $res_check_auth['top_menu_name'];
            $menuids_arr         = $res_check_auth['menuids_arr'];

            $this->_assign_menus($admin_top_menu_name);

            // 权限检测
            if (in_array(strtolower(CONTROLLER_NAME), explode(',', 'index'))) {
                if(in_array(ACTION_NAME, array('login','verify_code','logout','index')) ){
                    return; // 不做权限处理
                }
            }
            if($admin_session['group'] != 1){ // 非超级管理员
                if(count($menuids_arr) > 0 ){
                    $priv_mod = M('admin_auth');
                    $map      = array();
                    $map["role_id"] = $admin_session['group'];
                    $map["menu_id"] = array("in", $menuids_arr);
                    $r_data = $priv_mod->where($map)->select();        
                    if (!$r_data) {
                        // exit('权限不足');
                        $this->cpk_error('权限不足',array('backurl'=>U('index/index')));
                    }
                }
            }
        }
    }

    private function _assign_menus($admin_top_menu_name){
        C('ADMIN_TOP_MENU_NAME',$admin_top_menu_name);
        if(!common_is_pjax()){
            $left_menu = D("Admin/AdminMenu","Datamanager")->getAllMenu();
            $this->assign('left_menu', $left_menu);
            $top_menus = D("Admin/AdminMenu","Datamanager")->getTopAdminMenu();
            $this->assign('top_menus', $top_menus);
        }
    }

    public function _empty($method){
        if(C('CPK_FROM_MODULE_ADMIN')){
            // 来自模块或者插件的admin
            $module_name = CONTROLLER_NAME;
        }else{
            $module_name = "Admin";
        }
        // 映射的操作（存在于衍射列表）进行重新定位m,c,a
        if($this->action_list[$method] != '' && C('CPK_FROM_MODULE_ADMIN') ){
            list($to_controller,$to_action) = explode("/", $this->action_list[$method]);
            if($to_controller =='' || $to_action == ''){
                $this->cpk_error('action_list方法的格式错误,至少需要一个‘/’');
            }
            define('ADMIN_CONTROLLER_NAME',$to_controller);
            if(defined('PK_PLUGIN_NAME')){
                // 来自插件的admin链接
                $plugin_name = PK_PLUGIN_NAME;
                $module_name = common_replace_plugin_name_to_module_name(PK_PLUGIN_NAME);
                $this->_plugin_new_m_c_a($plugin_name,$module_name,$to_controller,$to_action);
            }else{
                // 来自模块的admin链接
                $this->_new_m_c_a($module_name,$to_controller,$to_action);
            }
        }else{
            // 非映射的操作（可能不存在，也可能是快速操作方法）

            // 如果来自插件的非映射操作
            if(defined('PK_PLUGIN_NAME')){
                // 不提供_before_和_after_方法，因为是AutoHandle如果里面有其他业务逻辑，请创建具体方法
                $file = './Application/Plugins/'.PK_PLUGIN_NAME.'/Datamanager/AutoHandleDatamanager.class.php';
                if(!is_file($file)){
                    $this->cpk_error('未找到'.$method.'方法<br>1、请确认链接：Admin/'.CONTROLLER_NAME.'/'.$method.'<br>2、请确认是否已将它放到action_list内(快速自动操作方法除外)<br>3、如果是快速自动操作方法,则无法进行快速操作,因未找到'.PK_PLUGIN_NAME.'分组下的AutoHandleDatamanager.class.php文件');
                }
                $thisConfig = D("Plugins://".PK_PLUGIN_NAME.'/AutoHandle','Datamanager')->getConfigData($method);
                if($thisConfig){
                    $this->_action_do_this($method,$thisConfig);
                }else{
                    $this->cpk_error('未找到'.$method.'方法<br>1、请确认链接：Admin/'.CONTROLLER_NAME.'/'.$method.'<br>2、请确认是否已将它放到action_list内(快速自动操作方法除外)<br>3、如果是快速自动操作方法,则未在AutoHandleDatamanager中未找到该方法'.$method);
                }
            // 来自模块的非映射操作
            }else{
                // 不提供_before_和_after_方法，因为是AutoHandle如果里面有其他业务逻辑，请创建具体方法
                $file = './Application/'.$module_name.'/Datamanager/AutoHandleDatamanager.class.php';
                if(!is_file($file)){
                    $this->cpk_error('未找到'.$method.'方法<br>1、请确认链接：Admin/'.CONTROLLER_NAME.'/'.$method.'<br>2、请确认是否已将它放到action_list内(快速自动操作方法除外)<br>3、如果是快速自动操作方法,则无法进行快速操作,因未找到'.$module_name.'分组下的AutoHandleDatamanager.class.php文件');
                }
                $thisConfig = D($module_name.'/AutoHandle','Datamanager')->getConfigData($method);
                if($thisConfig){
                    $this->_action_do_this($method,$thisConfig);
                }else{
                    $this->cpk_error('未找到'.$method.'方法<br>1、请确认链接：Admin/'.CONTROLLER_NAME.'/'.$method.'<br>2、请确认是否已将它放到action_list内(快速自动操作方法除外)<br>3、如果是快速自动操作方法,则未在AutoHandleDatamanager中未找到该方法'.$method);
                }
            }

        }
    }

    private function _new_m_c_a($module_name,$controller_name,$action_name){
        include_once APP_PATH. $module_name .'/Common/function.php';
        $file = './Application/'.$module_name.'/Admin/'.$controller_name.'Admin.class.php';
        if(!is_file($file)){
            $this->cpk_error('未找到'.$file);
        }
        $admin_controller = A($module_name.'/'.$controller_name,'Admin');
        if (!method_exists($admin_controller, $action_name)) {
            $this->cpk_error('未在'.$file.'中找到'.$action_name.'方法');
        }
        $admin_controller->$action_name();
    }

    private function _plugin_new_m_c_a($plugin_name,$module_name,$controller_name,$action_name){
        include_once APP_PATH. $module_name .'/Common/function.php';
        include_once APP_PATH."Plugins/". $plugin_name .'/Common/function.php';
        $file = './Application/Plugins/'.$plugin_name.'/Admin/'.$controller_name.'Admin.class.php';
        if(!is_file($file)){
            $this->cpk_error('未找到插件文件'.$file);
        }
        $admin_controller = A( 'Plugins://'.$plugin_name.'/'.$controller_name,'Admin');
        if (!method_exists($admin_controller, $action_name)) {
            $this->cpk_error('未在'.$file.'中找到'.$action_name.'方法');
        }
        $admin_controller->$action_name();
    }

    private function _action_do_this($method,$thisConfig){
        if(defined('PK_PLUGIN_NAME')){
            $HandleObject = PK_PLUGIN_NAME."HandleObject";
        }else{
            $HandleObject = "AdminAdminHandleObject"; // 其实命名AdminHandleObject更合理 @todo
        }
        $adminHandleObject = $this->visitor->$HandleObject();
        if(!$adminHandleObject){
            $this->cpk_error('请admin_visitor中注册'.$HandleObject);
        }

        switch ($thisConfig['action']) {
            case 'add':  // 此方法最后会被删除，采用create替代
                $res = $adminHandleObject->$method();
                if($res['error'] == 0 && $res['info'] != ""){
                    $backdata['id']      = $res;
                    $backdata['backurl'] = I('backurl') == '' ? '' : I('backurl');
                    $this->cpk_success($res['info'],$backdata);
                }else{
                    $this->cpk_error($res['info']);
                }
                break;
            case 'create':
                $res = $adminHandleObject->$method();
                if($res['error'] == 0 && $res['info'] != ""){
                    $backdata['id']      = $res;
                    $backdata['backurl'] = I('backurl') == '' ? '' : I('backurl');
                    $this->cpk_success($res['info'],$backdata);
                }else{
                    $this->cpk_error($res['info']);
                }
                break;
            case 'save':
                $id = I('id',0,'intval');
                $res = $adminHandleObject->$method($id);
                if($res['error'] == 0 && $res['info'] != ""){
                    $backdata['id']      = $id;
                    $backdata['backurl'] = I('backurl') == '' ? '' : I('backurl');
                    $this->cpk_success($res['info'],$backdata);
                }else{
                    $this->cpk_error($res['info']);
                }
                break;
            case 'delete':
                $id = I('id',0,'intval');
                $res = $adminHandleObject->$method($id);
                if($res['error'] == 0 && $res['info'] != ""){
                    $backdata['id']      = $id;
                    $backdata['backurl'] = I('backurl') == '' ? '' : I('backurl');
                    $this->cpk_success($res['info'],$backdata);
                }else{
                    $this->cpk_error($res['info']);
                }
                break;
            case 'batchdelete':
                $ids = I('ids','','trim');
                $res = $adminHandleObject->$method($ids);
                if($res['error'] == 0 && $res['info'] != ""){
                    $backdata['id']      = $id;
                    $backdata['backurl'] = I('backurl') == '' ? '' : I('backurl');
                    $this->cpk_success($res['info'],$backdata);
                }else{
                    $this->cpk_error($res['info']);
                }
                break;
            case 'ajax':
                $id = I('id',0,'intval');
                $res = $adminHandleObject->$method($id);
                if($res['error'] == 0 && $res['info'] != ""){
                    $this->cpk_success($res['info']);
                }else{
                    $this->cpk_error($res['info']);
                }
                break;
            default:
                # code...
                break;
        }
    }

}