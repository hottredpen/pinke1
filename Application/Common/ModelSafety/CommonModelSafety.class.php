<?php
namespace Common\ModelSafety;
class CommonModelSafety{

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