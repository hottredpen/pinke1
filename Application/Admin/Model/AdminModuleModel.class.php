<?php
namespace Admin\Model;
use Common\Model\CommonModel;
class AdminModuleModel extends CommonModel{

    const ADMIN_ADD      = 11;//管理员添加
    const ADMIN_SAVE     = 12;//管理员修改
    const ADMIN_DEL      = 13;//管理员删除

    const MODULE_INSTALL   = 1101; // 模块安装时的添加
    const MODULE_UPDATE    = 1102; // 模块更新
    const MODULE_UNINSTALL = 1103; // 模块卸载

    protected $tmp_data;
    protected $old_data;
    protected $scene_id;

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        
    );

    protected $_validate = array(
 
        // 卸载模块
        array('name', 'is_name_pass', '不存在该模块名', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),
        array('id', 'is_deleteadminnotown_pass', '你无法删除自己的账号', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),
        array('id', 'is_deleteadminnotsuper_pass', '你无法删除超级管理员的信息', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),
    );
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
 


}