<?php 
namespace Plugins\AdminTest\Admin;

class AdminTestDetailAdmin extends AdminTestBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    // 记录测试前的数据
    public function logging_origin_data(){
        $id         = I('id',0,'intval');
        $is_origin  = 1;
        $res_detail = D('Plugins://AdminTest/AdminTest','Service')->loggingAdminTestDetailData($id,$is_origin);
        if($res_detail['error']==0 && $res_detail['info'] != ""){
            $this->pk_success($res_detail['info']);
        }else{
            $this->pk_error($res_detail['info']);
        }
    }

    // 记录测试后的数据
    public function logging_update_data(){
        $id         = I('id',0,'intval');
        $is_origin  = 0;
        $res_detail = D('Plugins://AdminTest/AdminTest','Service')->loggingAdminTestDetailData($id,$is_origin);
        if($res_detail['error']==0 && $res_detail['info'] != ""){
            $this->pk_success($res_detail['info']);
        }else{
            $this->pk_error($res_detail['info']);
        }
    }

    public function show_test_assert_change(){
        $id          = I('id',0,'intval');
        $assert_data = D('Plugins://AdminTest/AdminTestDetail','Datamanager')->getAssertData($id);
        $this->pk_success('ok',array('assert_data'=>$assert_data));
    }

    // 
    public function assert_change_is_passed(){
        $id           = I('id',0,'intval');
        $assert_index = I('assert_index',0,'intval');
        $res          = D('Plugins://AdminTest/AdminTest','Service')->assertChangeIsPassed($id,$assert_index);
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info'],array('assert_status'=>$res['assert_status']));
        }else{
            $this->pk_error($res['info']);
        }
    }

}