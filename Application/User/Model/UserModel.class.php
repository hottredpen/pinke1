<?php
namespace User\Model;
use Think\Model;
class UserModel extends Model{

    const ADMIN_ADD        = 11; // 后台添加用户，密码默认为123456
    const ADMIN_SAVE       = 12; // 后台修改用户，无法修改密码，如果忘记密码只能走前台的忘记密码流程
    const ADMIN_DEL     = 13; // 删除用户

    private $tmp_data;
    private $old_data;

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员添加
        array('reg_time','time',self::ADMIN_ADD,'function'),
        array('reg_from','3',self::ADMIN_ADD),  // 来自后台导入

        array('update_time','time',self::ADMIN_SAVE,'function'),

    );

    protected $_validate = array(
        // 关键词回复添加
        array('username', 'is_username_pass', '已经存在用户名', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('nickname', 'is_notempty_pass', '昵称不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),

        array('id', 'is_id_pass', '错误的id', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('username', 'is_username_pass', '已经存在用户名', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('nickname', 'is_notempty_pass', '昵称不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        
    );

    protected function is_id_pass($id){
        $data = $this->where(array('id'=>$id))->find();
        if($data){
            $this->old_data = $data;
            return true;
        }
        return false;
    }


    protected function is_username_pass($username){
        if($this->old_data['id'] > 0){
            $has = $this->where(array('username'=>$username,'id'=>array('neq',$this->old_data['id'])))->find();
        }else{
            $has = $this->where(array('username'=>$username))->find();
        }
        if($has){
            return false;
        }else{
            return true;
        }
    }



}