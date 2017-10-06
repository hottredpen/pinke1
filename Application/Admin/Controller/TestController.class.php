<?php
namespace Admin\Controller;
use Common\Controller\CommonBaseController;
use Common\Util\PkTest;
/**
 * 后台一些方法的测试用地
 */
class TestController extends CommonBaseController {

    public function _initialize() {
        parent::_initialize();
        if(!APP_DEBUG){
            exit();
        }
    }

    public function addadmin(){

        $res = M('admin')->add(array('username'=>'user'.time()));

        dump($res);

    }


    public function start_ok_task_by_test_data_id(){
        $task_id      = I('task_id',0,'intval');

        $map['task_id'] = $task_id;
        $testdata         = D('Plugins://AdminTest/AdminTestData','Datamanager')->getData(1,100,$map);


        $adminBaseHandleObject = new \Admin\HandleObject\AdminAdminHandleObject($this->visitor->info['id']);

        $oo     = new PkTest();

        $oo->initTestData($testdata);

        $res = $oo->start_ok_test($adminBaseHandleObject);
        if($res['error'] == 0){
            if($res['info'] == '添加管理员成功'){
                $return = array('status'=>1,'assert_status'=>'success','assert_info'=>$res['info'],'info'=>'测试成功');
            }else{
                $return = array('status'=>1,'assert_status'=>'failed','assert_info'=>$res['info'],'info'=>'测试失败');
            }
        }else{
            $return = array('status'=>1,'assert_status'=>'failed','assert_info'=>$res['info'],'info'=>'出现意外');
        }

        $this->json($return);
    }
    public function start_task_by_test_data_id(){
        $task_id      = I('task_id',0,'intval');
        $test_data_id = I('test_data_id',0,'intval');


        $map['task_id'] = $task_id;
        $testdata         = D('Plugins://AdminTest/AdminTestData','Datamanager')->getData(1,100,$map);
        $cur_error_test   = D('Plugins://AdminTest/AdminTestData','Datamanager')->getInfo($test_data_id);

        // dump($cur_error_test);
        // exit();
        $adminBaseHandleObject = new \Admin\HandleObject\AdminAdminHandleObject($this->visitor->info['id']);

        $oo     = new PkTest();

        $oo->initTestData($testdata,$cur_error_test);

        $res = $oo->start_error_test($adminBaseHandleObject);
        
        

        if($res['error']){
            if($res['info'] == $cur_error_test['assert']){
                $return = array('status'=>1,'assert_status'=>'success','assert_info'=>$res['info'],'info'=>'断言成功');
            }else{
                $return = array('status'=>1,'assert_status'=>'failed','assert_info'=>$res['info'],'info'=>'断言失败');
            }
        }else{
            $return = array('status'=>1,'assert_status'=>'failed','assert_info'=>$res['info'],'info'=>'未制止错误','id'=>$res['id']);
        }

        $this->json($return);

    }

    public function start_task(){
        $task_id  = I('task_id',0,'intval');

        $map['task_id'] = $task_id;
        $testdata     = D('Plugins://AdminTest/AdminTestData','Datamanager')->getData(1,100,$map);

 
        $success_info = "添加管理员成功";
        // dump($testdata);
        $testTaskModel = D("Common/TestTask");
        // $testdata = $this->_getTestData_testid($test_task['model_custom_id']);
        $testTaskModel->initTestData($testdata,$success_info);

        $adminBaseHandleObject = new \Admin\HandleObject\AdminAdminHandleObject($this->visitor->info['id']);

        $testTaskModel->test_do($adminBaseHandleObject,'createAdmin');


        $outdata         = $testTaskModel->getOutData();


        $return_id       = $testTaskModel->getReturnId();


        $res = M('test_task_log')->add(array('content'=>serialize($outdata),'guid'=>$guid,'task_id'=>$test_task['id'],'return_id'=>$return_id));

        dump($outdata);


        dump($res);

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