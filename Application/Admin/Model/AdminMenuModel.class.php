<?php
namespace Admin\Model;
use Common\Model\CommonModel;
class AdminMenuModel extends CommonModel{

    const ADMIN_ADD      = 11;//管理员添加
    const ADMIN_SAVE     = 12;//管理员修改
    const ADMIN_DEL      = 13;//管理员删除

    const MODUELE_INSTALL = 1101; // 模块安装时的添加

    protected $tmp_data;
    protected $old_data;
    protected $scene_id;

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员添加
        array('addtime','time',self::ADMIN_ADD,'function'),
        array('updatetime','time',self::ADMIN_ADD,'function'),
        array('url','set_url',self::ADMIN_ADD,'callback'),
        array('controller_name','set_controller_name',self::ADMIN_ADD,'callback'),
        array('top_pid','set_top_pid',self::ADMIN_ADD,'callback'),
        
        //管理员修改
        array('updatetime','time',self::ADMIN_SAVE,'function'),
        array('url','set_url',self::ADMIN_SAVE,'callback'),
        array('controller_name','set_controller_name',self::ADMIN_SAVE,'callback'),
        array('top_pid','set_top_pid',self::ADMIN_SAVE,'callback'),

        // 模块安装时的添加
        array('addtime','time',self::MODUELE_INSTALL,'function'),
        array('updatetime','time',self::MODUELE_INSTALL,'function'),
        array('top_pid','set_top_pid',self::MODUELE_INSTALL,'callback'),
        array('controller_name','set_controller_name',self::MODUELE_INSTALL,'callback'),
        array('action_name','set_action_name',self::MODUELE_INSTALL,'callback'),
        
    );

    protected $_validate = array(
        //管理员添加
        array('name', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('name', 'is_notempty_pass', '菜单名不能空@1234@', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('name', 'is_filter_pass', '菜单名不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('pid', 'is_pid_pass', '错误的pid参数', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('module_name', 'is_module_name_pass', '模块名称不能为空', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('controller_name', 'is_only_char_num_underline_pass', '模块名只能是英文字母、数字、下划线', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('controller_name', 'get_controller_name', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('action_name', 'is_only_char_num_underline_pass', '方法名只能是英文字母、数字、下划线', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('action_name', 'get_action_name', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),


        //管理员修改
        array('id', 'is_id_pass', '错误的id参数', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('name', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('name', 'is_notempty_pass', '菜单名不能空', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('name', 'is_filter_pass', '菜单名不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('pid', 'is_pid_pass', '错误的pid参数', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('module_name', 'is_module_name_pass', '模块名称不能为空', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('controller_name', 'is_only_char_num_underline_pass', '模块名只能是英文字母、数字、下划线', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('controller_name', 'get_controller_name', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('action_name', 'is_only_char_num_underline_pass', '方法名只能是英文字母、数字、下划线', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('action_name', 'get_action_name', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        //管理员删除
        array('id', 'is_id_pass', '错误的id参数', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),
        array('id', 'is_nothassonid_pass', '先删除菜单下的子菜单才能删除', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),


        // 模块安装时的添加
        array('name', 'is_notempty_pass', '菜单名不能空@1234@', self::MUST_VALIDATE,'function',self::MODUELE_INSTALL),
        array('name', 'is_filter_pass', '菜单名不能包含特殊符号', self::MUST_VALIDATE,'function',self::MODUELE_INSTALL),
        array('pid', 'is_pid_pass', '错误的pid参数', self::MUST_VALIDATE,'callback',self::MODUELE_INSTALL),
        array('module_name', 'is_module_name_pass', '模块名称不能为空', self::MUST_VALIDATE,'callback',self::MODUELE_INSTALL),
        array('controller_name', 'is_only_char_num_underline_pass', '模块名只能是英文字母、数字、下划线', self::MUST_VALIDATE,'function',self::MODUELE_INSTALL),
        array('controller_name', 'get_controller_name', 'return_true', self::MUST_VALIDATE,'callback',self::MODUELE_INSTALL),
        array('url', 'get_url', 'return_true', self::MUST_VALIDATE,'callback',self::MODUELE_INSTALL),    

    );
    /**
     ***********************
     * 记录方法
     ***********************
     */
    protected function _after_insert($data, $options) {
        $id = $this->getLastInsID();
        admin_log('AdminMenu',self::ADMIN_ADD,$id,admin_session_admin_id(),'','',$data);
    }

    protected function _after_update($data, $options) {
        $id = $data['id'];
        admin_log('AdminMenu',self::ADMIN_SAVE,$id,admin_session_admin_id(),"",$this->old_data,$data);
    }
    protected function _after_delete($data, $options) {
        $id = $data['id'];
        admin_log('AdminMenu',self::ADMIN_DEL,$id,admin_session_admin_id(),"",$this->old_data,$data);
    }
    /**
     ***********************
     * 业务方法
     ***********************
     */
    protected function is_nothassonid_pass($id){
        $has = $this->where(array("pid"=>$id))->find();
        if($has){
            return false;
        }
        return true;
    }
    protected function is_id_pass($id){
        $data = $this->where(array('id'=>$id))->find();
        if($data){
            $this->old_data = $data;
            return true;
        }
        return false;
    }
    protected function is_pid_pass($pid){
        $this->tmp_data['pid'] = $pid;
        return true;
    }
    protected function get_controller_name($controller_name){
        $this->tmp_data['controller_name'] = ucfirst($controller_name);
        return true;
    }
    protected function get_action_name($action_name){
        $this->tmp_data['action_name'] = $action_name;
        return true;
    }
    protected function set_url(){
        return "admin/".$this->tmp_data['controller_name']."/".$this->tmp_data['action_name'];
    }
    protected function set_controller_name(){
        return $this->tmp_data['controller_name'];
    }

    protected function is_module_name_pass($module_name){
        if($module_name != ''){
            $this->tmp_data['module_name'] = $module_name;
            return true;
        }
        return false;
    }

    protected function get_url($url){
        list($module_name,$controller_name,$action_name) = explode("/", $url);
        $this->tmp_data['url'] = $url;
        $this->tmp_data['action_name'] = $action_name;
        return true;
    }

    protected function set_top_pid(){
        $pid = $this->tmp_data['pid'];
        if($pid > 0){
            $top_pid = $this->where(array('module_name'=>$this->tmp_data['module_name'],'pid'=>0))->getField('id'); //$this->where(array('id'=>$pid))->getField('top_pid');
        }else{
            $top_pid = 0; // top_id等于自己的id
        }
        return $top_pid;
    }

    protected function set_action_name(){
        return $this->tmp_data['action_name'];
    }


}