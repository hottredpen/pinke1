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

    public function createAdminGroup(){
        $adminGroupModel = D("Admin/AdminGroup");
        if (!$adminGroupModel->field('pid,title,icon,status,menu_auth,sort')->create($_POST,11)){
            return array("error"=>1,"info"=>$adminGroupModel->getError());
        }
        $res = $adminGroupModel->add();
        if($res){
            $admin_menu = $adminGroupModel->getAdminMenuAuth();
            $this->_update_admin_auth_hook($res,$admin_menu);
            return array("error"=>0,"info"=>"添加用户组成功","id"=>$res);
        }else{
            return array("error"=>1,"info"=>"添加用户组失败");
        }
    }
    public function saveAdminGroup($id){
        $adminGroupModel = D("Admin/AdminGroup");
        if (!$adminGroupModel->field('id,pid,title,icon,status,menu_auth,sort')->create($_POST,12)){
            return array("error"=>1,"info"=>$adminGroupModel->getError());
        }
        $res = $adminGroupModel->where(array('id'=>$id))->save();
        if($res){
            $admin_menu = $adminGroupModel->getAdminMenuAuth();
            $this->_update_admin_auth_hook($id,$admin_menu);
            return array("error"=>0,"info"=>"修改用户组成功","id"=>$id);
        }else{
            return array("error"=>1,"info"=>"修改用户组失败");
        }
    }

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
            return array("error"=>1,"info"=>"备份失败");
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


    private function _update_admin_auth_hook($group_id,$admin_menu){
        // 删除原有的权限
        $old_data  = M('admin_auth')->where(array('role_id'=>$group_id))->select();
        if(count($old_data) > 0){
            $res = M('admin_auth')->where(array('role_id'=>$group_id))->delete();
        }
        // 添加新的
        $all_isok = true;
        foreach ($admin_menu as $key => $value) {
            $add_data['role_id'] = $group_id;
            $add_data['menu_id'] = $value;
            M('admin_auth')->add($add_data);
        }
    }




}