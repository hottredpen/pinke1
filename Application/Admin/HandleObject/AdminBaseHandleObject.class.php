<?php
namespace Admin\HandleObject;
/**
 * AdminHandleObject
 * 管理员操作对象
 */
class AdminBaseHandleObject extends BaseHandleObject {
    protected $uid;
    function __construct($uid=0) {
        parent::__construct($uid);
        $this->uid = (int)$uid;
    }
    /**
     * 登录
     */
    public function login(){
        $adminModel = D('Admin');
        if (!$adminModel->field('loginusername,loginpassword,verify_code')->create($_POST,10)){
            return array("error"=>1,"info"=>$adminModel->getError());
        }
        $admin_id = $adminModel->getLoginUserId();
        $res = $adminModel->where(array('id'=>$admin_id))->save(); // 记录ip,及登录时间
        if($res){
            return array("error"=>0,"info"=>"登陆成功","id"=>$admin_id);
        }else{
            return array("error"=>1,"info"=>"网络开小差了");
        }
    }
    /**
     * 退出
     */
    public function logout(){
        session('admin', null);
        // todo 以下的用钩子删除对应模块内的
        unset($_SESSION['_store_sys_info_']);
        unset($_SESSION['_store_sys_user_info_']);
        return array("error"=>0,"info"=>"登出成功");
    }
}