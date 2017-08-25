<?php
namespace Admin\Model;
use Common\Model\CommonModel;
class AdminPluginModel extends CommonModel{

    const ADMIN_ADD  = 11;
    const ADMIN_SAVE = 12;
    const ADMIN_DEL  = 13;

    private $tmp_data;
    private $old_data;
    private $scene_id;

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(

        // 安装
        array('create_time','time',self::ADMIN_ADD,'function'),


    );

    protected $_validate = array(

        
        
    );

    protected function _after_insert($data, $options) {
        $id = $this->getLastInsID();
        admin_log('AdminPlugin',self::ADMIN_ADD,$id,admin_session_admin_id());
    }

    protected function _after_update($data, $options) {
        $id = $data['id'];
        admin_log('AdminPlugin',self::ADMIN_SAVE,$id,admin_session_admin_id());
    }

    protected function _after_delete($data, $options) {
        $id = $data['id'];
        admin_log('AdminPlugin',self::ADMIN_DEL,$id,admin_session_admin_id());
    }

}