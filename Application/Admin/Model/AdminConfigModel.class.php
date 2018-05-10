<?php
namespace Admin\Model;
use Common\Model\CommonModel;
class AdminConfigModel extends CommonModel{

    const ADMIN_ADD      = 11; //管理员添加
    const ADMIN_SAVE     = 12; //管理员修改
    const ADMIN_DEL      = 13; //管理员删除

    const ADMIN_SETTING_SAVE = 22; // setting中的多个值的修改

    protected $tmp_data;
    protected $old_data;
    protected $scene_id;
    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员添加
        array('create_time','time',self::ADMIN_ADD,'function'),
        array('update_time','time',self::ADMIN_ADD,'function'),
        array('options','set_options',self::ADMIN_ADD,'callback'),
        
        //管理员修改
        array('update_time','time',self::ADMIN_SAVE,'function'),

        // setting中的多个值的修改
        array('update_time','time',self::ADMIN_SETTING_SAVE,'function'),
        
    );

    protected $_validate = array(




        // setting保存
        // array('group', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_SETTING_SAVE),
        array('group', 'get_admin_setting_save_scene', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_SETTING_SAVE),
        array('group', 'is_group_pass', '错误的group参数', self::MUST_VALIDATE,'callback',self::ADMIN_SETTING_SAVE),

        // 添加
        // array('name', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('name', 'is_notempty_pass', '配置标识不能空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        // 修改
        array('id', 'is_id_pass', '错误的id参数', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('name', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('name', 'is_notempty_pass', '配置标识不能空', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        // 删除
        array('id', 'is_id_pass', '错误的id参数', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),


    );
    /**
     ***********************
     * 对外方法
     ***********************
     */
    public function getSettingPostData(){
        foreach ($this->tmp_data as $key => $value) {
            if($_POST[$value['name']] != null){
                $post[$key]['name']  = $value['name'];
                $post[$key]['value'] = $_POST[$value['name']];
            }
        }
        return $post;
    }

    /**
     ***********************
     * 记录方法
     ***********************
     */
    protected function _after_insert($data, $options) {
        $id = $this->getLastInsID();
        admin_log('AdminConfig',self::ADMIN_ADD,$id,admin_session_admin_id(),'','',$data);
    }

    protected function _after_update($data, $options) {
        if($this->scene_id != self::ADMIN_SETTING_SAVE){
            $id = $data['id'];
            admin_log('AdminConfig',self::ADMIN_SAVE,$id,admin_session_admin_id(),"",$this->old_data,$data);
        }
    }
    protected function _after_delete($data, $options) {
        $id = $data['id'];
        admin_log('AdminConfig',self::ADMIN_DEL,$id,admin_session_admin_id(),"",$this->old_data,$data);
    }
    /**
     ***********************
     * 业务方法
     ***********************
     */
    protected function is_id_pass($id){
        $data = $this->where(array('id'=>$id))->find();
        if($data){
            $this->old_data = $data;
            return true;
        }
        return false;
    }

    protected function is_group_pass($group){
        $data = $this->where(array('module'=>$group,'status'=>1))->select();
        if(count($data) == 0){
            return false;
        }
        $this->tmp_data = $data;
        return true;
    }
    protected function get_admin_setting_save_scene(){
        $this->scene_id = self::ADMIN_SETTING_SAVE;
        return true;
    }

    protected function set_options(){
        return ''; // todo
    }



}