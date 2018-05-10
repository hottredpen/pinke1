<?php
namespace Admin\HandleObject;
/**
 * AdminHandleObject
 * 管理员操作对象
 */
class AdminAdminHandleObject extends BaseHandleObject{

	protected $uid;
    function __construct($uid=0) {
        parent::__construct($uid);
        $this->uid = (int)$uid;
    }

    public function installModule(){
        $module_name = I('name','','trim');

        $moduleModel = D('Admin/AdminModule');
        C('TRANS_START_METHOD',__METHOD__);
        C('TRANS_END_METHOD',__METHOD__);  
        // 开始事务
        common_plus_start_trans(__METHOD__,$moduleModel);
        // 添加到admin_module
        $res_module  = D('Admin/AdminModule','Service')->installLocalModule($module_name);

        // 添加菜单
        $module_id  = $res_module['id'];
        $res_menus  = D('Admin/AdminMenu','Service')->createMenusByLocalFile($module_name,$module_id);

        if($res_module['error'] == 0 && $res_menus['error'] == 0 && $res_module['info'] != '' && $res_menus['info'] !=''){
            common_plus_commit_trans(__METHOD__,$moduleModel);
            return $res_module;
        }else{
            common_plus_rollback_trans(__METHOD__,$moduleModel);
            return array('error'=>1,'info'=>'安装失败-'.common_format_all_service_error_info(array($res_module,$res_menus)));
        }
    }

    public function uninstallModule(){
        $id          = I('id',0,'intval');
        $moduleModel = D('Admin/AdminModule');
        C('TRANS_START_METHOD',__METHOD__);
        C('TRANS_END_METHOD',__METHOD__);  
        // 开始事务
        common_plus_start_trans(__METHOD__,$moduleModel);
        // 卸载模块
        $res_module = D('Admin/AdminModule','Service')->uninstallModuleByModuleId($id);
        // 删除菜单
        $res_menus  = D('Admin/AdminMenu','Service')->deleteMenusByModuleId($id);

        if($res_module['error'] == 0 && $res_menus['error'] == 0 && $res_module['info'] != '' && $res_menus['info'] !=''){
            common_plus_commit_trans(__METHOD__,$moduleModel);
            return $res_module;
        }else{
            common_plus_rollback_trans(__METHOD__,$moduleModel);
            return array('error'=>1,'info'=>'卸载失败-'.common_format_all_service_error_info(array($res_module,$res_menus)));
        }
    }


    public function saveSetting(){
        $group = I('group','common','trim');

        $adminConfigModel = D("Admin/AdminConfig");
        if (!$adminConfigModel->field('group')->create($_POST,22)){
            return array("error"=>1,"info"=>$adminConfigModel->getError());
        }
        $allok = true;
        $post_data = $adminConfigModel->getSettingPostData();

        foreach ($post_data as $key => $value) {
            $res = $adminConfigModel->where(array('name'=>$value['name'],'group'=>$group))->setField(array('value'=>$value['value'],'update_time'=>time()));
            if(!$res){
                $allok = false;
            }
        }
        if($allok){
            admin_log('AdminConfig',22,0,admin_session_admin_id(),"",array(),$post_data);
            return array("error"=>0,"info"=>"保存成功");
        }else{
            return array("error"=>1,"info"=>"保存失败");
        }
    }

    // public function createAdminGroup(){
    //     $adminGroupModel = D("Admin/AdminGroup");
    //     $thisConfig      = D("Admin/AdminGroup",'ModelSafety')->getConfigData('createAdminGroup');
    //     if (!$adminGroupModel->field($thisConfig['field'])->create($_POST,$thisConfig['key'])){
    //         return array("error"=>1,"info"=>$adminGroupModel->getError());
    //     }
    //     $res = $adminGroupModel->add();
    //     if($res){
    //         $admin_menu = $adminGroupModel->getAdminMenuAuth();
    //         $this->_update_admin_auth_hook($res,$admin_menu);
    //         return array("error"=>0,"info"=>"添加用户组成功","id"=>$res);
    //     }else{
    //         return array("error"=>1,"info"=>"添加用户组失败");
    //     }
    // }
    // public function updateAdminGroup($id){
    //     $adminGroupModel = D("Admin/AdminGroup");
    //     if (!$adminGroupModel->field('id,pid,title,icon,status,menu_auth,sort')->create($_POST,12)){
    //         return array("error"=>1,"info"=>$adminGroupModel->getError());
    //     }
    //     $res = $adminGroupModel->where(array('id'=>$id))->save();
    //     if($res){
    //         $admin_menu = $adminGroupModel->getAdminMenuAuth();
    //         $this->_update_admin_auth_hook($id,$admin_menu);
    //         return array("error"=>0,"info"=>"修改用户组成功","id"=>$id);
    //     }else{
    //         return array("error"=>1,"info"=>"修改用户组失败");
    //     }
    // }

    /**
     * 备份数据库
     */
    public function exportDatabase(){
        $adminDatabaseModel = D("Admin/AdminDatabase");
        if (!$adminDatabaseModel->field('export_database_step,tables')->create($_POST,11)){
            return array("error"=>1,"info"=>$adminDatabaseModel->getError());
        }
        $res = $adminDatabaseModel->add();
        if($res){
            return array("error"=>0,"info"=>"备份成功","id"=>$res);
        }else{
            return array("error"=>1,"info"=>"备份失败333");
        }
    }
    /**
     * 还原数据库
     */
    public function importDatabase(){
        $adminDatabaseModel = D("Admin/AdminDatabase");
        if (!$adminDatabaseModel->field('filename,import_database_step')->create($_POST,10)){
            return array("error"=>1,"info"=>$adminDatabaseModel->getError());
        }
        return array("error"=>0,"info"=>"还原成功","id"=>$id);
    }


    // private function _update_admin_auth_hook($group_id,$admin_menu){
    //     // 删除原有的权限
    //     $old_data  = M('admin_auth')->where(array('role_id'=>$group_id))->select();
    //     if(count($old_data) > 0){
    //         $res = M('admin_auth')->where(array('role_id'=>$group_id))->delete();
    //     }
    //     // 添加新的
    //     $all_isok = true;
    //     foreach ($admin_menu as $key => $value) {
    //         $add_data['role_id'] = $group_id;
    //         $add_data['menu_id'] = $value;
    //         M('admin_auth')->add($add_data);
    //     }
    // }




}