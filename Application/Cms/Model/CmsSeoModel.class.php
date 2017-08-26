<?php
namespace Cms\Model;
use Common\Model\CommonModel;
class CmsSeoModel extends CommonModel{

    protected $tmp_name;
    protected $tmp_pid;

    const ADMIN_ADD      = 11;//管理员添加
    const ADMIN_SAVE     = 12;//管理员修改
    const ADMIN_DEL   = 13;//管理员删除
    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员添加
        array('addtime','time',self::ADMIN_ADD,'function'),
        array('modulename','set_modulename',self::ADMIN_ADD,'callback'),
        //管理员修改
        array('updatetime','time',self::ADMIN_SAVE,'function'),
    );

    protected $_validate = array(
        //管理员添加
        array('pid', 'get_pid', 'callback_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('name', 'is_notempty_pass', '名称不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('name', 'is_filter_pass', '名称不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('name', 'get_name', 'callback_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('module', 'is_notempty_pass', '模块不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('module', 'is_only_char_num_underline_pass', '模块名只能是英文字母、数字、下划线', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('action', 'is_notempty_pass', '动作不能为空，如果是新模块请填写index', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('action', 'is_only_char_num_underline_pass', '方法名只能是英文字母、数字、下划线', self::MUST_VALIDATE,'function',self::ADMIN_ADD),

        


    );

    protected function get_name($name){
        $this->tmp_name = $name;
        return true;
    }

    protected function get_pid($pid){
        $this->tmp_pid = $pid;
        return true;
    }

    protected function set_modulename(){
        if((int)$this->pid == 0){
            return $this->tmp_name;
        }
        return '';
    }

}