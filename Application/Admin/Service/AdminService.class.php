<?php
namespace Admin\Service;
class AdminService {
    public function loginByUserNameAndPassword($username,$password,$verify_code){
        $adminModel             = D('Admin');
        $login['loginusername'] = $username;
        $login['loginpassword'] = $password;
        $login['verify_code']   = $verify_code;
        if (!$adminModel->field('loginusername,loginpassword,verify_code')->create($login,10)){
            $admin_data['username'] = $login['loginusername'];
            $this->_login_log("username_password",$admin_data,$adminModel->getError(),0);
            return array("error"=>1,"info"=>$adminModel->getError());
        }
        $admin_data = $adminModel->getLoginAdminData();
        if($admin_data['id'] > 0){
            $res = $adminModel->where(array('id'=>$admin_data['id']))->save();
            session('admin', array(
                'id'        => $admin_data['id'],
                'role_id'   => $admin_data['role_id'],
                'group'     => $admin_data['group'],
                'rolename'  => admin_local_admin_group_name($admin_data['group']),
                'username'  => $admin_data['username'],
            ));
            $this->_login_log("username_password",$admin_data,'登录成功',1);
            return array("error"=>0,"info"=>"登录成功",'admin_id'=>$admin_data['id']);
        }else{
            return array("error"=>1,"info"=>"未知错误");
        }
    }

    private function _login_log($login_type,$admin_data,$login_info,$login_status){
        $add_log['login_type']   = $login_type;
        $add_log['admin_id']     = (int)$admin_data['id'];
        $add_log['username']     = $admin_data['username'];
        $add_log['login_info']   = $login_info;
        $add_log['login_status'] = $login_status;
        $add_log['ip']           = get_client_ip();
        $add_log['create_time']  = time();
        $res = M('admin_login_log')->add($add_log);
    }
}