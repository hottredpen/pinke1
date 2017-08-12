<?php 
namespace Plugins\AdminTest\Admin;
use Admin\Controller\BackController;

class AdminTestBaseAdmin extends BackController {

    protected function _initialize(){
        parent::_initialize();
    }

    public function index(){

        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('admin自动化测试')
        		->SetTabNav(array(
        				array('title'=>'配置','href'=>'javascript:;'),
        				array('title'=>'单元测试','href'=>U('Admin/AdminTest/task')),
                        array('title'=>'集成测试','href'=>U('Admin/AdminTest/group_task')),
        				array('title'=>'测试记录','href'=>U('Admin/AdminTest/task_log')),
        			))
                ->setPostUrl(U('Admin/createAdmin'))
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');


    }



}