<?php
namespace Admin\Controller;
/**
 * 后台一些方法的测试用地
 */
class TestController extends BackController {

    public function _initialize() {
        parent::_initialize();
        if(!APP_DEBUG){
            exit();
        }
    }

    public function index(){
    	// 删除pid找不到的menu

    	$data = M('admin_menu')->select();

    	foreach ($data as $key => $value) {
            M('admin_menu')->where(array('id'=>$value['id']))->setField('url',"admin/".$value['controller_name'].'/'.$value['action_name']);

    	// 	if($value['pid'] > 0){
	    // 		$pid_data = M('admin_menu')->where(array('id'=>$value['pid']))->find();
	    // 		if(!$pid_data){
	    // 			dump($value);
					// M('admin_menu')->where(array('id'=>$value['id']))->delete();

	    // 		}
    	// 	}

    	}





    }



}