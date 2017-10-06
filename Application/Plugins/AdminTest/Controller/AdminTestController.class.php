<?php
namespace Plugins\AdminTest\Controller;
use Admin\Controller\BackController;

class AdminTestController extends BackController {

    protected function _initialize() {
        parent::_initialize();
        // 如果找不到方法，重新定义到新的Admin控制器
        $this->action_list =  array(

            // 配置
            'setting'                => 'AdminTestBase/index',
            
            // 测试任务
            'task'                   => 'AdminTestTask/task',
            'addAdminTestTask'       => 'AdminTestTask/addAdminTestTask',
            'editAdminTestTask'      => 'AdminTestTask/editAdminTestTask',
            
            // 
            'group_task'             => 'AdminTestTask/group_task',
            
            'testDataList'           => 'AdminTestData/testDataList',
            'addAdminTestData'       => 'AdminTestData/addAdminTestData',
            
            
            
            
            'addTestDataForTask'     => 'AdminTestData/addTestDataForTask',
            'editAdminTestData'      => 'AdminTestData/editAdminTestData',
            
            'before_start_task'      => 'AdminTestData/before_start_task',
            
            'test_data_group_list'   => 'AdminTestDataGroup/test_data_group_list',
            
            'addAdminTestDataGroup'  => 'AdminTestDataGroup/addAdminTestDataGroup',
            'editAdminTestDataGroup' => 'AdminTestDataGroup/editAdminTestDataGroup',


        );
    }
}