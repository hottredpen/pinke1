<?php
namespace Cms\Model;
use Think\Model;
class CmsDocumentModel extends Model{

    const ADMIN_ADD  = 11; // 添加
    const ADMIN_SAVE = 12; // 修改

    private $tmp_data;
    private $old_data;
    private $scene_id;

    //字段衍射
    protected $_map = array(
                                'pid'      => 'category_id', // 
                                'seo_keys' => 'keywords',
                                'seo_desc' => 'description',
                        );
    //修改插入后自动完成
    protected $_auto = array(
        // 添加
        array('create_time','set_create_time',self::ADMIN_ADD,'callback'),
        array('listdata','common_filter_textarea',self::ADMIN_ADD,'function'),
        array('otherdata','common_filter_textarea',self::ADMIN_ADD,'function'),
        // 修改
        array('create_time','set_create_time',self::ADMIN_SAVE,'callback'),
        array('update_time','time',self::ADMIN_SAVE,'function'),
        array('listdata','common_filter_textarea',self::ADMIN_SAVE,'function'),
        array('otherdata','common_filter_textarea',self::ADMIN_SAVE,'function'),

    );

    protected $_validate = array(
        // 添加
        array('category_id', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('category_id', 'is_category_id_pass', '请选择文章分类', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('title', 'is_notempty_pass', '标题不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('title', 'is_filter_pass', '标题不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('create_date','get_create_date', 'callback_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        // 修改
        array('id', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('id', 'is_id_pass', '错误的id', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('category_id', 'is_category_id_pass', '请选择文章分类', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('title', 'is_notempty_pass', '标题不能为空', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('title', 'is_filter_pass', '标题不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('create_date','get_create_date', 'callback_true', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),

    );
    /**
     ***********************
     * 记录方法
     ***********************
     */
    protected function _after_insert($data, $options) {
        $id = $this->getLastInsID();
        admin_log('CmsDocument',self::ADMIN_ADD,$id,admin_session_admin_id(),'','',$data);
    }

    protected function _after_update($data, $options) {
        $id = $data['id'];
        admin_log('CmsDocument',self::ADMIN_SAVE,$id,admin_session_admin_id(),"",$this->old_data,$data);
    }
    protected function _after_delete($data, $options) {
        $id = $data['id'];
        admin_log('CmsDocument',self::ADMIN_DEL,$id,admin_session_admin_id(),"",$this->old_data,$data);
    }
    /**
     ***********************
     * 业务方法
     ***********************
     */
    protected function is_id_pass($id){
        $info = $this->where(array('id'=>$id))->find();
        if($info){
            $this->old_data = $info;
            return true;
        }
        return false;  
    }
    protected function is_category_id_pass($category_id){
        // todo 只能是最后一级
        if($category_id > 0){
            return true;
        }
        return false;
    }

    protected function get_create_date($create_date){
        $time = strtotime($create_date);
        if($time > 1000){
            $this->tmp_data['create_time'] = $time;
        }else{
            $this->tmp_data['create_time'] = time();
        }
        return true;
    }

    protected function set_create_time(){
        return $this->tmp_data['create_time'];
    }


}