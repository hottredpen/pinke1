<?php
namespace Admin\ModelSafety;
use Common\ModelSafety\CommonModelSafety;
class AdminGroupModelSafety extends CommonModelSafety{

    public $_action_data = array();

    function  __construct(){
        $this->_action_data = array(
            'name'        => '用户组',
            'not_allow_field' => 'money',
            'actions'     => array(
                'createAdminGroup' => array(
                    'action' => 'create',
                    'field'  => 'pid,title,icon,status,menu_auth,sort',
                    'key'    => 11
                ),
                'updateAdminGroup' => array(
                    'action' => 'update',
                    'field'  => 'id,pid,title,icon,status,menu_auth,sort',
                    'key'    => 12,
                ),
                'ajaxAdminGroup' => array(
                    'action' => 'ajax',
                    'field'  => 'status',
                    'key'    => 12,
                ),
                'deleteAdminGroup' => array(
                    'action' => 'delete',
                    'field'  => 'id',
                    'key'    => 13,
                ),
            ),
            'logs'         => array(
                11 => array(
                    'info'   => '[admin_id|admin_local_admin_id_name]【[admin_id|admin_get_group_name_by_admin_id]】添加了新的用户组',
                    'status' => 1
                ),
                12 => array(
                    'info'   => '[admin_id|admin_local_admin_id_name]【[admin_id|admin_get_group_name_by_admin_id]】修改了用户组',
                    'status' => 1
                ),
                13 => array(
                    'info'   => '[admin_id|admin_local_admin_id_name]【[admin_id|admin_get_group_name_by_admin_id]】删除了用户组',
                    'status' => 1
                ),  
            )
        );
    }
}