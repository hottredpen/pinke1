<?php
namespace User\Model;
use Think\Model;
class UserLoginModel extends Model{

    const USER_LOGIN_BY_PASSWORD  = 1102; // 密码登录

    protected $tmp_data;
    protected $old_data;

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        // 密码登录
        array('update_time','time',self::USER_LOGIN_BY_PASSWORD,'function'),

    );

    protected $_validate = array(
        // 密码登录
        array('username', 'is_username_pass', '不存在用户名', self::MUST_VALIDATE,'callback',self::USER_LOGIN_BY_PASSWORD),
        array('password', 'is_password_pass', '密码错误', self::MUST_VALIDATE,'callback',self::USER_LOGIN_BY_PASSWORD),
        
    );

    public function getLoginUserId(){
        return (int)$this->tmp_data['user_id'];
    }


    protected function is_username_pass($username){
        $has = M('user')->where(array('username'=>$username))->find();
        if($has){
            $this->old_data = $has;
            $this->tmp_data['user_id'] = $has['id'];
            return true;
        }else{
            return false;
        }
    }

    protected function is_password_pass($password=""){
        return true;
    }



}