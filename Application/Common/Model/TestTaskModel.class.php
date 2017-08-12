<?php
namespace Common\Model;
use Think\Model;
class TestTaskModel extends Model{


	protected $prePostData;
    protected $okPostData;//全部正确的提交数据
	protected $outData;
    protected $success_info_array = array("保存成功","添加成功");


    private $test_success_info;

    private $tmp_data;
    private $test_return_id;

    const TASK_ADD           = 11;
    const TASK_EDIT          = 12;

    //字段衍射
    protected $_map = array(
                                
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //新增
        array('addtime','getVisitorId',self::TASK_ADD,'function'),

    );

    protected $_validate = array(


    );


    public function test_do($handleObj,$action_name){
        //先测试正确的（因为输出错误后后面都会出错）
        $_POST                         = $this->_format_post_data($this->okPostData);
        $res                           = $handleObj->$action_name();
        $this->test_return_id          = $res['id'];
        $_success_outdata              = $this->_takeOutdata(array('testtitle'=>"检测测试数据里的正确数据",'errorinfo'=>$this->test_success_info),$res,$_POST);
        $this->outData['add_success_test'] = $_success_outdata;
        //再测试错误的
        $postdata          = $this->getPrePostData();
        foreach ($postdata as $key => $value) {
            $_POST                = $this->_format_post_data($value['postdata']);
            unset($_POST['IS_TEST_VISITOR_ID']);
            // 访问者信息
            if($value['postdata']['IS_TEST_VISITOR_ID'] > 0){
                $_SESSION['IS_TEST_VISITOR_ID'] = (int)$value['postdata']['IS_TEST_VISITOR_ID'];
            }
            $res[$key]                  = $handleObj->$action_name();
            $_error_outdata[$key]       = $this->_takeOutdata($value,$res[$key],$_POST);
            // 销毁虚拟访问者信息
            unset($_SESSION['IS_TEST_VISITOR_ID']);
        }
        $this->outData['add_error_test'] = $_error_outdata;

    }

    public function getReturnId(){
        return (int)$this->test_return_id;
    }


    private function _format_post_data($postdata){
        foreach ($postdata as $key2 => $value2) {
            if(strstr($value2,'@rand')){
                $postdata[$key2] = str_replace("@rand", common_filter_strs(guid()), $value2);
            }
        }
        return $postdata;
    }


    private function _takeOutdata($value,$res,$postData){
        $_outdata['testtitle'] = "<font style='color:blue;' >".$value['testtitle']."</font>";
        if($value['errorinfo']==$res['info'] || in_array($res['info'], $this->success_info_array)){
            $_outdata['statusinfo']   = "<font style='color:green;' >测试通过</font>";
            $_outdata['_testvalue']   = "测试错误数据=>".$value['errorvalue'];
            $_outdata['_right_value'] = "参考数据=>".$value['right_value'];
            $_outdata['_need']        = "预测错误返回=>".$value['errorinfo'];
            $_outdata['_return']      = "实际错误返回=>".$res['info'];
            $_outdata['_help']        = "如果和上一个错误相同，可能本次没有报错";
            $_outdata['_visitor_id']  = (int)$_SESSION['IS_TEST_VISITOR_ID'];
            $_outdata['postdata']     = $postData;
        }else{
            $_outdata['statusinfo']   = "<font style='color:red;' >未通过</font>";
            $_outdata['_testvalue']   = "测试错误数据=>".$value['errorvalue'];
            $_outdata['_right_value'] = "参考数据=>".$value['right_value'];
            $_outdata['_need']        = "预测错误返回=>".$value['errorinfo'];
            $_outdata['_return']      = "实际错误返回=>".$res['info'];
            $_outdata['_help']        = "如果和上一个错误相同，可能本次没有报错";
            $_outdata['_visitor_id']  = (int)$_SESSION['IS_TEST_VISITOR_ID'];
            $_outdata['postdata']     = $postData;
        }
        return $_outdata;
    }
    /**
     * 初始化测试数据
     */
    public function initTestData($testdata,$success_info){
    	$this->_last_postdata($testdata);
        $this->test_success_info = $success_info;
        
    }
    public function getOutData(){
        return $this->outData;
    }
    /**
     * 查看每次提交的数据及错误的信息（调试查看）
     */
    public function getPrePostData(){
    	return $this->prePostData;
    }

    public function getOkPostData(){
        return $this->okPostData;
    }


    private function _last_postdata($testdata){
	    $last_array = array();
	    foreach ($testdata as $key => $value) {
	    	list($_postfield,$_postvalue,$_postbadvalue,$_errorinfo,$_testtitle,$_pre_togger_field_arr,$_visitor_info) = $value;

            $last_array[$key]['thiskey']              = $key;
            $last_array[$key]['errorinfo']            = $_errorinfo;
            $last_array[$key]['right_value']          = $_postvalue;
            $last_array[$key]['errorvalue']           = $_postbadvalue;
            $last_array[$key]['errorfield']           = $_postfield;
            $last_array[$key]['testtitle']            = $_testtitle;
            $last_array[$key]['pre_togger_field_arr'] = $_pre_togger_field_arr;
            $last_array[$key]['visitor_info']        = $_visitor_info;
	    }

	    $key_array = array();
	    foreach ($testdata as $key => $value) {
	    	if(!in_array($value[0], $key_array)){
	    		array_push($key_array, $value[0]);
                // $value[0]（字段）  $value[1]（正确值）
	    		$right_value[$value[0]] = $value[1];
	    	}
	    }

        $this->okPostData = $right_value;

	    foreach ($testdata as $key => $value) {
	    	$last_array[$key]['postdata'] = $right_value;
	    }

        //将错误的信息替换
        foreach ($last_array as $key => $value) {
            foreach ($value['postdata'] as $key2 => $value2) {
                // postdata 字段中对应的错误字段
                if($key2 == $value['errorfield']){
                    // 替换上错误的字段
                    $last_array[$key]['postdata'][$key2] = $value['errorvalue'];
                }
                // 前置调节字段
                if($value['pre_togger_field_arr'][$key2] != null){
                    $last_array[$key]['postdata'][$key2]  = $value['pre_togger_field_arr'][$key2];
                }

                // 访问者信息
                $last_array[$key]['postdata']['IS_TEST_VISITOR_ID']  = $value['visitor_info']['id'];

            }
        }
	    $this->prePostData = $last_array;
    }


}