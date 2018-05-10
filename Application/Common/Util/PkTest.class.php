<?php
/**
 *  pk测试类
 *
 * 
 */
namespace Common\Util;
class PkTest {

    public $okPostData;
    public $curPostData; // 本次测试的提交数据
    public $test_success_info;


    private $sence_id;
    private $test_data_group_index;
    private $test_data_items_index;

    public function init($model="",$sence_id=11,$local_no=""){
        $local_no_arr     = explode("-", $local_no);

        $this->model                 = $model;
        $this->sence_id              = $sence_id;
        $this->test_data_group_index = (int)$local_no_arr[0];
        $this->test_data_items_index = (int)$local_no_arr[1];
    }

    public function start(){

    }


    public function initTestData($testdata=array(),$cur_error_test=array(),$success_info=""){
        $this->_last_postdata($testdata,$cur_error_test);
        $this->test_success_info = $success_info;
    }

    public function initOkTestData($testdata){
        $this->_last_postdata($testdata);
        $this->test_success_info = $success_info;
    }

    public function start_error_test($handleObj,$action_name){
        C('PK_TESTING',1);
        $_POST                         = $this->_format_post_data($this->curPostData);
        $res                           = $handleObj->$action_name();
        return $res;
    }

    public function start_ok_test($handleObj,$action_name){
        C('PK_TESTING',1);
        $_POST                         = $this->_format_post_data($this->okPostData);
        $res                           = $handleObj->$action_name();
        return $res;
    }


    private function _format_post_data($postdata){
        foreach ($postdata as $key2 => $value2) {
            if(strstr($value2,'@rand')){
                $postdata[$key2] = str_replace("@rand", common_filter_strs(guid()), $value2);
            }
        }
        return $postdata;
    }
    public function _last_postdata($testdata,$cur_error_test){
        $last_array = array();
        foreach ($testdata as $key => $value) {
            // list($_postfield,$_postvalue,$_postbadvalue,$_errorinfo,$_testtitle,$_pre_togger_field_arr,$_visitor_info) = $value;

            $last_array[$key]['thiskey']              = $key;
            $last_array[$key]['errorinfo']            = $value['assert'];
            $last_array[$key]['right_value']          = $value['field_success_value'];
            $last_array[$key]['errorvalue']           = $value['field_error_value'];
            $last_array[$key]['errorfield']           = $value['field_name'];
            $last_array[$key]['testtitle']            = $value['title'];
            $last_array[$key]['pre_togger_field_arr'] = $_pre_togger_field_arr;
            $last_array[$key]['visitor_info']         = $_visitor_info;
        }
        $key_array = array();
        foreach ($testdata as $key => $value) {
            if(!in_array($value['field_name'], $key_array)){
                array_push($key_array, $value['field_success_value']);
                $right_value[$value['field_name']] = $value['field_success_value']; 
            }
        }

        $this->okPostData = $right_value;
        if(isset($cur_error_test)){
            $curPostData =  $right_value;
            
            foreach ($curPostData as $key => $value) {
                // postdata 字段中对应的错误字段
                if($key == $cur_error_test['field_name']){
                    // 替换上错误的字段
                    $curPostData[$key] = $cur_error_test['field_error_value'];
                }
                // 前置调节字段
                // if($value['pre_togger_field_arr'][$key2] != null){
                //     $last_array[$key]['postdata'][$key2]  = $value['pre_togger_field_arr'][$key2];
                // }

                // // 访问者信息
                // $last_array[$key]['postdata']['IS_TEST_VISITOR_ID']  = $value['visitor_info']['id'];

            }

            $this->curPostData = $curPostData;
        }


    }


}
