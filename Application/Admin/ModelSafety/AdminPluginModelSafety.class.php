<?php
namespace Admin\ModelSafety;
use Common\ModelSafety\CommonModelSafety;
class AdminPluginModelSafety extends CommonModelSafety{

    public $_action_data = array();

    function  __construct(){
        $this->_action_data = array(
            'name'        => '插件',
            'actions'     => array(
                'createAdminMenu' => array(
                    'action' => 'create',
                    'field'  => 'name,pid,controller_name,action_name,data',
                    'key'    => 11
                ),
                'updateAdminMenu' => array(
                    'action' => 'update',
                    'field'  => 'id,name,pid,controller_name,action_name,data',
                    'key'    => 12,
                ),
                'ajaxAdminMenu' => array(
                    'action' => 'ajax',
                    'field'  => 'id,name,pid,controller_name,action_name,data,ordid,status,top_pid',
                    'key'    => 12,
                ),
                'deleteAdminMenu' => array(
                    'action' => 'delete',
                    'field'  => 'id',
                    'key'    => 13,
                ),
            ),
            'logs'         => array(
                11 => array(
                    'info'   => '[admin_id|admin_local_admin_id_name]【[admin_id|admin_get_group_name_by_admin_id]】添加了新的菜单',
                    'status' => 1
                ),
                12 => array(
                    'info'   => '[admin_id|admin_local_admin_id_name]【[admin_id|admin_get_group_name_by_admin_id]】修改了菜单',
                    'status' => 1
                ),
                13 => array(
                    'info'   => '[admin_id|admin_local_admin_id_name]【[admin_id|admin_get_group_name_by_admin_id]】删除了菜单',
                    'status' => 1
                ),  
            )
        );
    }

    public function getConfigData($method){
        $thisConfig = $this->_action_data['actions'][$method];
        if($thisConfig){
            $thisConfig['name']  = $this->_action_data['name'];
            $thisConfig['model'] = str_replace($thisConfig['action'], "" , $method);
            return $thisConfig;
        }else{
            return false;
        }
    }

    public function replaceTplByData($data,$before_data,$after_data){
        $tpl_data = $this->_action_data['logs'][$data['scene_id']];
        if($tpl_data['status'] == 1 && $tpl_data['info'] != ''){
            $tpl = $tpl_data['info'];
            $tpl = str_replace("[admin_id]", $data['admin_id'], $tpl);
            $tpl = str_replace("[record_id]", $data['record_id'], $tpl);
            $tpl = common_trans_log_tpl_by_self_func($tpl,$data);
            $tpl = common_trans_log_tpl_by_before_data($tpl,$before_data);
            $tpl = common_trans_log_tpl_by_after_data($tpl,$after_data);
            // 替换
        }else{
            $tpl = false;
        }
        return $tpl;
    }


}