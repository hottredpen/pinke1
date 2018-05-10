<?php
namespace User\Model;
use Think\Model;
class UserMsgTplModel extends Model{

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
        array('add_time','time',self::ADMIN_ADD,'function'),
        array('update_time','time',self::ADMIN_ADD,'function'),
        array('content','common_filter_editor_content',self::ADMIN_ADD,'function'),

        // 修改
        array('update_time','time',self::ADMIN_SAVE,'function'),
        array('content','common_filter_editor_content',self::ADMIN_SAVE,'function'),
    );

    protected $_validate = array(
        // 关键词回复添加

        
    );

}