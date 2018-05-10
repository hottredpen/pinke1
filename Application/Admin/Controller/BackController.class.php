<?php
/**
 * 通用后台
 */
namespace Admin\Controller;
use Common\Controller\CommonBaseController;
class BackController extends CommonBaseController {

    public    $visitor;
    protected $action_list; // 带有映射动作时，对url进行重新制定跳转

    protected function _initialize() {
        header("Content-Type: text/html; charset=utf-8");
        parent::_initialize();
        if(!in_array(ACTION_NAME, array('verify_code'))){
            $this->visitor = new \Admin\Visitor\admin_visitor();
        }
        $this->_checkAuth();
    }
    /**
     * 查看是否具有权限
     * todo 待优化，
     */
    private function _checkAuth(){
        $admin_session = session('admin');
        if ( (!isset($admin_session) || !$admin_session) && !in_array(ACTION_NAME, array('login','verify_code','logout')) ) {
            $this->redirect('index/login');
        }else{

            $current_url    = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME) ;
            $res_check_auth = admin_get_auth_data_by_url($current_url);

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
                        $this->pk_error('权限不足',array('backurl'=>U('index/index')));
                    }
                }
            }
        }
    }

    private function _assign_menus($admin_top_menu_name){
        C('ADMIN_TOP_MENU_NAME',$admin_top_menu_name);
        if(!common_is_pjax()){
            $left_menu = admimn_get_all_menu();
            $this->assign('left_menu', $left_menu);
            $top_menus = admin_get_top_admin_menu();
            $this->assign('top_menus', $top_menus);
        }
    }

    /**
     * action_list的映射方法
     *
     * @author hottredpen
     * @date   2018-04-04
     * @param  [type]     $method [description]
     * @return [type]             [description]
     */
    public function _empty($method){
        if(C('PK_IS_ADMIN')){
            if($this->action_list[$method] == ''){
                $this->pk_error('在'.C('PK_MODULE_NAME').'或'.C('PK_PLUGIN_NAME').'的action_list中不存在'.$method."方法");
            }
        }
        if($this->action_list[$method] != ''){
            list($to_controller,$to_action) = explode("/", $this->action_list[$method]);
            if($to_controller == '' || $to_action == ''){
                $this->pk_error('action_list方法的格式错误,满足‘ "c" => "a/b" ’的格式');
            }
            C('PK_ADMIN_CONTROLLER_NAME',$to_controller);
            C('PK_ADMIN_ACTION_NAME',$to_action);
            if(C('PK_PLUGIN_NAME')){
                $this->_plugin_new_m_c_a(); // 来自插件的admin链接
            }else{
                $this->_new_m_c_a(); // 来自模块的admin链接
            }
        }else{
            dump("empty(var)");
            exit();
        }
    }

    private function _new_m_c_a(){
        include_once APP_PATH. C('PK_MODULE_NAME') .'/Common/function.php';
        $file = APP_PATH.C('PK_MODULE_NAME').'/Admin/'.C('PK_ADMIN_CONTROLLER_NAME').'Admin.class.php';
        if(!is_file($file)){
            $this->pk_error('未找1到'.$file);
        }
        $admin_controller = A(C('PK_MODULE_NAME').'/'.C('PK_ADMIN_CONTROLLER_NAME'),'Admin');
        $action_name      = C('PK_ADMIN_ACTION_NAME');
        if (!method_exists($admin_controller, $action_name)) {
            $this->_quick_handle_action(); // 被封装的操作方法（也称快速操作方法----单表的增删改）
        }else{
            $admin_controller->$action_name(); // 具体存在的操作方法
        }
    }

    private function _plugin_new_m_c_a(){
        include_once APP_PATH. C('PK_MODULE_NAME') .'/Common/function.php'; // 加载模块函数
        include_once APP_PATH."Plugins/". C('PK_PLUGIN_NAME') .'/Common/function.php'; // 加载插件函数
        $file = APP_PATH.'Plugins/'.C('PK_PLUGIN_NAME').'/Admin/'.C('PK_ADMIN_CONTROLLER_NAME').'Admin.class.php';
        if(!is_file($file)){
            $this->pk_error('未找到插件admin的入口文件'.$file);
        }
        $admin_controller = A( 'Plugins://'.C('PK_PLUGIN_NAME').'/'.C('PK_ADMIN_CONTROLLER_NAME'),'Admin');
        $action_name      = C('PK_ADMIN_ACTION_NAME');
        if (!method_exists($admin_controller, $action_name)) {
            $this->_quick_handle_action(); // 被封装的操作方法（也称快速操作方法----单表的增删改）
        }else{
            $admin_controller->$action_name(); // 具体存在的操作方法
        }
    }

    private function _action_do_this($method,$thisConfig){
        if(C('PK_PLUGIN_NAME')){
            $HandleObject = C('PK_PLUGIN_NAME')."HandleObject";
        }else{
            $HandleObject = "AdminAdminHandleObject"; // 其实命名AdminHandleObject更合理 @todo
        }
        $adminHandleObject = $this->visitor->$HandleObject();
        if(!$adminHandleObject){
            $this->pk_error('请admin_visitor中注册'.$HandleObject);
        }

        switch ($thisConfig['action']) {
            case 'create':
                $res = $adminHandleObject->$method();
                if($res['error'] == 0 && $res['info'] != ""){
                    $backdata['id']      = $res['id']; // 默认封装的操作方法中,新增操作都会返回一个id
                    $backdata['backurl'] = I('backurl') == '' ? '' : I('backurl');
                    $this->pk_success($res['info'],$backdata);
                }else{
                    $this->pk_error($res['info']);
                }
                break;
            case 'update':
                $id  = I('id',0,'intval');
                $res = $adminHandleObject->$method($id);
                if($res['error'] == 0 && $res['info'] != ""){
                    $backdata['id']      = $id;
                    $backdata['backurl'] = I('backurl') == '' ? '' : I('backurl');
                    $this->pk_success($res['info'],$backdata);
                }else{
                    $this->pk_error($res['info']);
                }
                break;
            case 'delete':
                $id = I('id',0,'intval');
                $res = $adminHandleObject->$method($id);
                if($res['error'] == 0 && $res['info'] != ""){
                    $backdata['id']      = $id;
                    $backdata['backurl'] = I('backurl') == '' ? '' : I('backurl');
                    $this->pk_success($res['info'],$backdata);
                }else{
                    $this->pk_error($res['info']);
                }
                break;
            case 'batchdelete':
                $ids = I('ids','','trim');
                $res = $adminHandleObject->$method($ids);
                if($res['error'] == 0 && $res['info'] != ""){
                    $backdata['ids']     = $ids;
                    $backdata['backurl'] = I('backurl') == '' ? '' : I('backurl');
                    $this->pk_success($res['info'],$backdata);
                }else{
                    $this->pk_error($res['info']);
                }
                break;
            case 'ajax':
                $id  = I('id',0,'intval');
                $res = $adminHandleObject->$method($id);
                if($res['error'] == 0 && $res['info'] != ""){
                    $this->pk_success($res['info']);
                }else{
                    $this->pk_error($res['info']);
                }
                break;
            default:
                $this->pk_error('无此操作类型'.$thisConfig['action']);
                break;
        }
    }

    private function _is_not_has_pre_word(){
        $keywords_arr = array('create','update','ajax','delete','do'); // 满足快速操作的命名前缀词
        $not_has      = true;
        foreach ($keywords_arr as $key => $value) {
            if(strstr(C('PK_ADMIN_ACTION_NAME'),$value)){
                $not_has = false;
            }
        }
        return $not_has;
    }

    private function _quick_handle_action(){
        if($this->_is_not_has_pre_word()){
            $this->pk_error(C('PK_ADMIN_ACTION_NAME').'不在指定操作里面,是否忘记添加该方法');
        }
        // 如果来自插件的操作,从对应的ModelSafety获取快速操作的配置
        if(C('PK_PLUGIN_NAME')){
            $file = APP_PATH.'Plugins/'.C('PK_PLUGIN_NAME').'/ModelSafety/'.C('PK_ADMIN_CONTROLLER_NAME').'ModelSafety.class.php';
            if(!is_file($file)){
                $this->pk_error('未找到'.$file);
            }
            $thisConfig = D("Plugins://".C('PK_PLUGIN_NAME')."/".C('PK_ADMIN_CONTROLLER_NAME'),'ModelSafety')->getConfigData(C('PK_ADMIN_ACTION_NAME'));
            if($thisConfig){
                $this->_action_do_this(C('PK_ADMIN_ACTION_NAME'),$thisConfig);
            }else{
                $this->pk_error('未12找到'.C('PK_ADMIN_ACTION_NAME').'方法<br>1、请确认链接：'.MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME.'<br>2、请确认是否已将它放到action_list内(快速自动操作方法除外)<br>3、未在'.$file.'中找到'.C('PK_ADMIN_ACTION_NAME'));
            }
        // 来自模块的操作,从对应的ModelSafety获取快速操作的配置
        }else{
            $file = APP_PATH.C('PK_MODULE_NAME').'/ModelSafety/'.C('PK_ADMIN_CONTROLLER_NAME').'ModelSafety.class.php';
            if(!is_file($file)){
                $this->pk_error('未找到'.$file);
            }
            $thisConfig = D(C('PK_MODULE_NAME')."/".C('PK_ADMIN_CONTROLLER_NAME'),'ModelSafety')->getConfigData(ACTION_NAME);
            if($thisConfig){
                $this->_action_do_this(C('PK_ADMIN_ACTION_NAME'),$thisConfig);
            }else{
                $this->pk_error('未1找到'.C('PK_ADMIN_ACTION_NAME').'方法<br>1、请确认链接：'.MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME.'<br>2、请确认是否已将它放到action_list内(快速自动操作方法除外)<br>3、未在'.$file.'中找到'.C('PK_ADMIN_ACTION_NAME'));
            }
        }
    }
}