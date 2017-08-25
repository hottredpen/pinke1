<?php
namespace Admin\Model;
use Common\Model\CommonModel;
class AdminModel extends CommonModel{

    const ADMIN_LOGIN      = 10; // 用户登陆
    const ADMIN_ADD        = 11; // 超级管理员添加管理员
    const ADMIN_SAVE       = 12; // 超级管理员修改管理员
    const ADMIN_DEL        = 13; // 超级管理员删除管理员

    private $password;

    private $tmp_data;
    private $old_data;
    private $scene_id;

    private $tmp_login_admin_data = array();

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        // 登录
        array('update_time','time',self::ADMIN_LOGIN,'function'),
        array('last_ip','get_client_ip',self::ADMIN_LOGIN,'function'),
        

        // 超级管理员添加管理员
        array('create_time','time',self::ADMIN_ADD,'function'),
        array('password','set_password',self::ADMIN_ADD,'callback'),

        // 超级管理员修改管理员
        array('update_time','time',self::ADMIN_SAVE,'function'),
        array('password','set_password',self::ADMIN_SAVE,'callback'),

    );

    protected $_validate = array(
        // 登录(以下字段不存在于数据表中)
        array('loginusername', 'get_admin_login_scene', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_LOGIN),
        array('loginusername', 'is_notempty_pass', '登录用户名必填', self::MUST_VALIDATE,'function',self::ADMIN_LOGIN),
        array('loginpassword', 'is_notempty_pass', '登录密码必填', self::MUST_VALIDATE,'function',self::ADMIN_LOGIN),
        array('verify_code', 'check_verify', '验证码错误', self::MUST_VALIDATE, 'function',self::ADMIN_LOGIN),
        array('loginusername', 'is_loginusername_pass', '用户名或密码错误', self::MUST_VALIDATE,'callback',self::ADMIN_LOGIN),
        array('loginpassword', 'is_loginpassword_pass', '用户名或密码错误', self::MUST_VALIDATE,'callback',self::ADMIN_LOGIN),


        // 超级管理员添加管理员
        array('username', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('username', 'is_notempty_pass', '用户名不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('username', 'is_usernamenothas_pass', '用户名已存在', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('username', 'is_filter_pass', '用户名不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('password', 'is_passwordlength_pass', '密码长度必须大于4个字符', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('repassword', 'is_repassword_pass', '两次密码输入不一致', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('email', 'is_notempty_pass', '邮箱不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('email', 'is_email_format_pass', '错误的邮箱格式', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('group', 'is_group_pass', '请选择所属分组', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('group', 'is_not_add_super_group_pass', '只能有一位超级管理员', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),


        // 超级管理员修改管理员
        array('id', 'is_adminid_pass', '错误的id参数', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('id', 'is_admincouldedit_pass', '你无法修改超级管理员的信息', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('username', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('username', 'is_notempty_pass', '用户名不能为空', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('username', 'is_usernamenothas_pass', '用户名已存在', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('username', 'is_filter_pass', '用户名不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('password', 'is_passwordlength_pass', '密码长度必须大于4个字符', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('repassword', 'is_repassword_pass', '两次密码输入不一致', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('email', 'is_notempty_pass', '邮箱不能为空', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('email', 'is_email_format_pass', '错误的邮箱格式', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('status', 'is_not_change_super_status_pass', '修改时不能禁用超级管理员', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('group', 'is_group_pass', '请选择所属分组', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('group', 'is_not_edit_not_own_super_group_pass', '只能有一位超级管理员', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        

        // 超级管理员删除管理员
        array('id', 'is_adminid_pass', '错误的id参数', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),
        array('id', 'is_deleteadminnotown_pass', '你无法删除自己的账号', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),
        array('id', 'is_deleteadminnotsuper_pass', '你无法删除超级管理员的信息', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),
        
    );
    /**
     ***********************
     * 对外方法
     ***********************
     */
    public function getLoginUserId(){
        return $this->tmp_login_admin_data['id'];
    }
    /**
     ***********************
     * 记录方法
     ***********************
     */
    protected function _after_insert($data, $options) {
        $id = $this->getLastInsID();
        admin_log('Admin',self::ADMIN_ADD,$id,admin_session_admin_id(),'','',$data);
    }

    protected function _after_update($data, $options) {
        if(isset($this->scene_id) && $this->scene_id != self::ADMIN_LOGIN){
            $id = $data['id'];
            admin_log('Admin',self::ADMIN_SAVE,$id,admin_session_admin_id(),"",$this->old_data,$data);
        }
    }
    protected function _after_delete($data, $options) {
        $id = $data['id'];
        admin_log('Admin',self::ADMIN_DEL,$id,admin_session_admin_id(),"",$this->old_data,$data);
    }
    /**
     ***********************
     * 业务方法
     ***********************
     */
    // protected function is_form_token_pass(){
    //     $token      = $_POST['form_token'];
    //     $token_salt =  $_POST['form_token_salt'];
    //     $ok_token   = md5(get_client_ip().$token_salt.strtolower(MODULE_NAME.CONTROLLER_NAME."addadmin"));
    //     if($ok_token == $token){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }

    protected function set_password(){
        return $this->_md5_password($this->password);
    }

    private function _md5_password($password,$verify){
        return md5($password.md5($verify).C('AUTHCODE'));
    }

    protected function get_admin_login_scene(){
        $this->scene_id = self::ADMIN_LOGIN;
        return true;
    }
    protected function is_loginusername_pass($username){
        $admin_data = $this->where(array('username'=>$username))->find();
        if($admin_data){
            $this->tmp_login_admin_data = $admin_data;
            return true;
        }else{
            return false;
        }
    }

    protected function is_loginpassword_pass($password){
        $admin_data   = $this->tmp_login_admin_data;
        $password_md5 = $this->_md5_password($password,$admin_data['verify']);
        if($admin_data['password'] == $password_md5){
            session('admin', array(
                'id'        => $admin_data['id'],
                'role_id'   => $admin_data['role_id'],
                'group'     => $admin_data['group'],
                'rolename'  => admin_local_admin_group_name($admin_data['group']),
                'username'  => $admin_data['username'],
            ));
            admin_log("Admin",10,$admin_data['id'],$admin_data['id'],$admin_data['username']."登录成功");
            return true;
        }else{
            admin_log("Admin",10,$admin_data['id'],$admin_data['id'],$admin_data['username']."登录失败，密码错误");
            return false;
        }
    }




    protected function is_usernamenothas_pass($username){
        $has = $this->where(array("username"=>$username))->find();
        if($has){
            if($has['id'] == (int)$this->old_data['id'] ){
                return true;
            }
            return false;
        }
        return true;
    }

    protected function is_passwordlength_pass($password){
        if(strlen($password) > 4){
            $this->password = $password;
            return true;
        }
        return false;
    }

    protected function is_repassword_pass($repassword){
        if($repassword == $this->password){
            return true;
        }
        return false;
    }

    protected function is_adminid_pass($id){
        $data = $this->where(array("id"=>$id))->find();
        if($data){
            $this->old_data = $data;
            return true;
        }
        return false;
    }

    protected function is_admincouldedit_pass($id){
        $adminid = admin_session_admin_id();
        if($adminid == $id){
            return true;
        }
        if($this->old_data['group'] == 1){
            return false;
        }else{
            return true;
        }
    }

    protected function is_deleteadminnotsuper_pass(){
        if($this->old_data['group'] == 1){
            return false;
        }else{
            return true;
        }
    }

    protected function is_deleteadminnotown_pass($id){
        $admin   = session("admin");
        $adminid = $admin['id'];
        if($adminid == $id){
            return false;
        }
        return true;
    }

    protected function is_group_pass($group){
        if($group <= 0){
            return false;
        }
        return true;
    }

    protected function is_not_add_super_group_pass($group){
        if($group == 1){
            return false;
        }
        return true;
    }

    protected function is_not_edit_not_own_super_group_pass($group){
        if($group == 1){
            if($this->old_data['group'] == 1){
                return true;
            }
            return false;
        }
        return true;
    }

    protected function is_not_change_super_status_pass($status){
        if($status == 0){
            if($this->old_data['group'] == 1){
                return false;
            }
            return true;
        }
        return true;
    }

}