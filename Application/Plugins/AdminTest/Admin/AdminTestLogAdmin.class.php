<?php 
namespace Plugins\AdminTest\Admin;

class AdminTestLogAdmin extends AdminTestBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function start_ok_task_by_local_no(){
        $test_id  = I('test_id',0,'intval');
        $local_no = I('local_no','0-1','trim');
        $is_real_do = true;
        $res      = $this->_do_handle($test_id,$local_no,$is_real_do);
        $this->pk_json_return($res);
    }
    public function start_task_by_local_no(){
        $test_id    = I('test_id',0,'intval');
        $local_no   = I('local_no','0-1','trim');
        $is_real_do = false;
        $res        = $this->_do_handle($test_id,$local_no,$is_real_do);
        $this->pk_json_return($res);
    }

    // public function assert_change_data(){
    //     $test_id    = I('test_id',0,'intval');
    //     $assert_local_no   = I('assert_local_no','0-1','trim');
    //     $res = $this->_assert_tables_change($test_id,$assert_local_no);
    //     $this->json($res);
    // }

    private function _do_handle($test_id,$local_no,$is_real_do){
        $dd           = explode("-", $local_no);
        $testLogModel = D('Plugins://AdminTest/AdminTestLog');

        $test['test_id']               = $test_id;     // 测试的id
        $test['is_real_do']            = $is_real_do;  // 是否进行数据真实修改
        $test['test_data_group_index'] = (int)$dd[0];  // 当前测试的数据组
        $test['test_data_items_index'] = (int)$dd[1];  // 当前数据组里的第几项验证item

        // 测试前的检测
        if (!$testLogModel->create($test,1111)){
            return array("error"=>1,"info"=>$testLogModel->getError());
        }
        if($is_real_do){
            $res_log = $testLogModel->add();
        }

        $testReturn = $testLogModel->getTestHandelReturn();

        if($testReturn['assert_status'] == 0 && $testReturn['need_log'] == 1){
            $res_log = $testLogModel->add();
        }

        return $testReturn;
    }

    // private function _assert_tables_change($test_id,$assert_local_no){
    //     $dd           = explode("-", $assert_local_no);
    //     $testLogModel = D('Plugins://AdminTest/AdminTestLog');

    //     $test['test_id']               = $test_id;
    //     // $test['is_real_do']            = $is_real_do;
    //     $test['test_data_group_index'] = (int)$dd[0];
    //     $test['test_data_items_index'] = (int)$dd[1];

    //     // 测试前的检测
    //     if (!$testLogModel->create($test,2222)){
    //         return array("error"=>1,"info"=>$testLogModel->getError());
    //     }
    //     // if($is_real_do){
    //     $res_log = $testLogModel->add();
    //     // }

    //     $testReturn = $testLogModel->getTestHandelReturn();

    //     if($testReturn['assert_status'] == 0 && $testReturn['handle_return_id'] > 0){
    //         $res_log = $testLogModel->add();
    //     }

    //     return $testReturn;
        
    // }
}