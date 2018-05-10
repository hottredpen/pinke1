<?php
namespace Plugins\AdminTest\Model;
use Common\Model\CommonModel;
use Common\Util\PkTest;
class AdminTestBeforeModel extends CommonModel{

    const ADMIN_TEST_BEFORE     = 11; // 测试前的数据添加


    protected $tmp_data;
    protected $old_data;
    protected $scene_id;
    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员添加
        array('create_time','time',self::ADMIN_TEST_BEFORE,'function'),
        array('uuid','set_uuid',self::ADMIN_TEST_BEFORE,'callback'),
        array('model','set_model',self::ADMIN_TEST_BEFORE,'callback'),
        array('field','set_field',self::ADMIN_TEST_BEFORE,'callback'),
        array('value','set_value',self::ADMIN_TEST_BEFORE,'callback'),
        array('data','set_data',self::ADMIN_TEST_BEFORE,'callback'),
        
    );

    protected $_validate = array(
        array('post_data', 'get_post_data', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_TEST_BEFORE),
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
    /**
     ***********************
     * 业务方法
     ***********************
     */

    protected function get_post_data(){
        $post_data = $_POST;

        $this->tmp_data['uuid'] = $post_data['uuid'];

        $new_post_data = array();
        foreach ($post_data as $key => $value) {
            list($model,$field) = explode("___", $key);
            if($field != ""){
                $new_post_data[$model][$field] = $value;
                $this->tmp_data['model'] = $model;
                $this->tmp_data['field'] = $field;
                $this->tmp_data['value'] = $value;
            }
        }
        return true; 
    }

    protected function set_uuid(){
        return $this->tmp_data['uuid'];
    }

    protected function set_model(){
        return $this->tmp_data['model'];
    }

    protected function set_field(){
        return $this->tmp_data['field'];
    }

    protected function set_value(){
        return $this->tmp_data['value'];
    }

    protected function set_data(){
        $where = array();
        $where[$this->tmp_data['field']] = $this->tmp_data['value'];
        $data = M($this->tmp_data['model'])->where($where)->select();
        return serialize($data);
    }



}