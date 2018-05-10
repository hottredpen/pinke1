<?php
namespace Plugins\AdminTest\Model;
use Common\Model\CommonModel;

class AdminTestModel extends CommonModel{

    const ADMIN_ADD     = 11; // 数据添加
    const ADMIN_SAVE    = 12; // 数据修改


    protected $tmp_data;
    protected $old_data;
    protected $scene_id;

    protected $_pk_is_init = true; // 默认数据都已经初始化完毕
    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        // 添加
        array('create_time','time',self::ADMIN_ADD,'function'),
        array('is_init','set_is_init',self::ADMIN_ADD,'callback'),
        
        // 修改
        array('update_time','time',self::ADMIN_SAVE,'function'),
        array('is_init','set_is_init',self::ADMIN_SAVE,'callback'),
        
    );

    protected $_validate = array(

        // 添加
        array('success_post_data', 'get_init_status', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),

        // 修改
        array('success_post_data', 'get_init_status', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),

    );
    /**
     ***********************
     * 对外方法
     ***********************
     */

    /**
     ***********************
     * 记录方法
     ***********************
     */
    protected function _after_insert($data, $options) {

    }
    protected function _after_update($data, $options) {

    }
    protected function _after_delete($data, $options) {

    }

    protected function get_init_status($success_post_data){
        $success_post_data = unserialize($success_post_data);
        foreach ($success_post_data as $key => $value) {
            if($this->_has_kk($value)){
                $this->_pk_is_init = false;
            }
        }
        return true;
    }

    private function _has_kk($value){
        if(preg_match('/{{([a-zA-Z_.]+)}}/',$value)){
            return true;
        }
        return false;
    }

    protected function set_is_init(){
        if($this->_pk_is_init){
            return 1;
        }
        return 0;
    }

    
}