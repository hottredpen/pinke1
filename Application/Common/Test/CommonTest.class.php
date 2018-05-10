<?php
namespace Common\Test;
class CommonTest{

    protected $_visitor_id = 0; // 行为访问者
    protected $_scene_id   = 0; // 模型场景
    protected $_data_group = 0; // 场景内的测试数据组
    protected $_test_data  = array(); // 测试的数据

    protected $_post_default_value = array();

    public function getHandleObject(){
        return $this->_config_data['handle_object'];
    }

    public function getIsPlugin(){
        return $this->_config_data['is_plugin'];
    }

    public function getModuleName(){
        return $this->_config_data['module_name'];
    }

    public function getContrllerName(){
        return $this->_config_data['controller_name'];
    }

    public function getUnitTestData($sence_id=11,$group_id=0,$test_id=0){
        $data['sence_id'] = $sence_id;
        $data['group_id'] = $group_id;
        $data['data']     = $this->_format_data_by_test_id($this->_config_data['units'][$sence_id]['test_data'][$group_id],$group_id,$test_id);
        $data['success_assert'] = $this->_config_data['units'][$sence_id]['success_assert'];
        return $data;
    }
    
    public function getAssertDataBySenceId($sence_id=0){
        return $this->_config_data['units'][$sence_id]['assert_data'];
    }

    public function getLogOriginData($sence_id=11){
        return $this->_config_data['units'][$sence_id]['log_origin_data'];
    }

    public function getLogUpdateData($sence_id=11){
        return $this->_config_data['units'][$sence_id]['log_update_data'];
    }

    public function formPostItems($sence_id=11,$group_id=0){
        $before_form = $this->_config_data['units'][$sence_id]['test_data'][$group_id];
        // 测试的items目前只支持text和textarea
        $items_arr      = array();
        $this->_post_default_value = array();
        foreach ($before_form as $key => $value) {
            list($name,$type,$title,$tip,$options,$extra)  = $value;
            if(!isset($this->_post_default_value[$name])){
                $item[$key] =  array(
                    'name'    => $name,
                    'type'    => $type,
                    'title'   => $title,
                    'tip'     => $tip,
                    'options' => $options,
                    'extra'   => $extra
                );
                $this->_post_default_value[$name] = $options['success_value'];
                array_push($items_arr,$item[$key]);
            }
        }
        return $items_arr;
    }

    public function getPostDefaultValue(){
        return $this->_post_default_value;
    }

    private function _item_options_format_for_test($item,$sence_id=11){
        list($name,$type,$title,$tip,$options,$extra)  = $item;
        return array(
            'name'    => $name,
            'type'    => $type,
            'title'   => $title,
            'tip'     => $tip,
            'options' => $options,
            'extra'   => $extra
        );
    }
    private function _format_data_by_test_id($data=array(),$group_id=0,$test_id=0){
        $index = 0;
        // dump($data);
        foreach ($data as $key => $value) {
            list($name,$type,$title,$tip,$options,$extra) = $value;
            // $format_data[$key]['model_name']           = "AdminModel";
            $format_data[$key]['title']                = $options['assert_title'];
            $format_data[$key]['field_name']           = $name;
            $format_data[$key]['field_success_value']  = admin_test_format_field_var($options['success_value']);
            // $format_data[$key]['field_success_value']  = admin_test_format_post_var($test_id,$options['success_value']);//$this->_getValueFromUUID($_postvalue,$uuid);
            $format_data[$key]['field_error_value']    = $options['error_value'];//$_postbadvalue;
            $format_data[$key]['assert']               = $options['success_assert'];
            $format_data[$key]['local_no']             = $group_id.'-'.$index;
            $index++;
        }
        return $format_data;
    }
    public function getTestData($scene_id=0,$test_group_index=0,$test_item_index,$test_id=0){
        $data             = $this->_config_data['units'][$scene_id]['test_data'][$test_group_index];
        $format_data      = $this->_format_data_by_test_id($data,$test_group_index,$test_id);
        $arr['test_data'] = $format_data;
        $arr['cur_error'] = $format_data[$test_item_index];
        return $arr;
    }
    // todo 待废弃
    public function beforeFormItems($sence_id=11){
        $before_form = $this->_config_data['units'][$sence_id]['test_config']['before_form'];
        $items_arr   = array();
        foreach ($before_form as $key => $value) {
            array_push($items_arr,$this->_form_item_format($value,$sence_id));
        }
        return $items_arr;
    }
    // todo 待废弃
    private function _form_item_format($item,$sence_id){
        list($name,$type,$title,$tip,$options,$extra)  = $item;

        return array(
            'name'    => $name,
            'type'    => $type,
            'title'   => $title,
            'tip'     => $tip,
            'options' => $options,
            'extra'   => $extra
        );
    }
    public function get_assert_data($sence_id=11){
        $data =  $this->_config_data['units'][$sence_id]['assert_data'];
        return $data;
    }
    public function getSuccessAssert($sence_id=0){
        return $this->_config_data['units'][$sence_id]['success_assert'];
    }
    public function getHandleAction($sence_id=0){
        return $this->_config_data['units'][$sence_id]['handle_action'];
    }
    public function getTestTitle($sence_id=0){
        return $this->_config_data['units'][$sence_id]['test_title'];
    }
    public function initTestData(){
        // $this->_visitor_id = $visitor_id;
        // $this->_scene_id   = $scene_id;
        // $this->_data_group = $data_group;
        // $this->_test_data  = $this->_config_data['units'][$scene_id][$data_group];
        $testModel = D('Test');
        if(!$testModel->create($this->_config_data,100)){
            return array("error"=>1,"info"=>$testModel->getError());
        }
        return $testModel;

    }
    public function getConfigData(){
        return $this->_config_data;
    }
    // public function getActionName($sence_id=0){
    //     return $this->_config_data['units'][$sence_id]['handle_action'];
    // }
}