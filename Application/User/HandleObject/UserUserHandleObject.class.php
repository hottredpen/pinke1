<?php
namespace User\HandleObject;
/**
 * AdminHandleObject
 * 管理员操作对象
 */
class UserUserHandleObject{
	protected $uid;
    function __construct($uid=0) {
        $this->uid = (int)$uid;
    }

    public function login($username="",$password=""){
    	$userModel  = D('User/User');
    	$post_data['username'] = $username;
    	$post_data['password'] = $password;
        if (!$userModel->field('username,password')->create($post_data,202)){
            return array("error"=>1,"info"=>$userModel->getError());
        }
        $old_data = $userModel->getOldData();
        $user_data = D('User/User','Datamanager')->getInfoForApp($old_data['id']);
        $res = $userModel->where(array('id'=>$old_data['id']))->save();
        if($res){
        	S($old_data['token'],$old_data['id']);
        	return array('error'=>0,'info'=>'登录成功','id'=>$old_data['id'],'user'=>$user_data,'token'=>$old_data['token']);
        }else{
        	return array('error'=>1,'info'=>'账号错误');
        }
    }

    public function updateUserAndShopData(){
        $user_id     = common_session_user_id();
        $userModel   = D('User/User');
        $_POST['id'] = $user_id;
        if (!$userModel->field('id,field_name,cover_id,nickname,qq,wechat,email,shop_name,shop_address,shop_fax,shop_fixed_phone')->create($_POST,20002)){
            return array("error"=>1,"info"=>$userModel->getError());
        }
        $res = $userModel->where(array('id'=>$_POST['id']))->save();
        if($res){
            return array('error'=>0,'info'=>'更新成功');
        }else{
            return array('error'=>1,'info'=>'更新失败');
        }
    }

}