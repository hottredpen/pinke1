<?php
namespace Plugins\AdminTest\Controller;
use Admin\Controller\BackController;

class AdminTestController extends BackController {

    protected function _initialize() {
        parent::_initialize();
        // 如果找不到方法，重新定义到新的Admin控制器
        $this->action_list =  array(
            
            'index'                  => 'AdminTest/index',  // local 
            'admin_test_list'        => 'AdminTest/admin_test_list',

            'addAdminTest'           => 'AdminTest/addAdminTest',
            'editAdminTest'          => 'AdminTest/editAdminTest',
            'createAdminTest'        => 'AdminTest/createAdminTest',
            'updateAdminTest'        => 'AdminTest/updateAdminTest',

            'startAdminTest'         => 'AdminTest/startAdminTest',
            'init_post_data'         => 'AdminTest/init_post_data',

            'start_task_by_local_no' => 'AdminTestLog/start_task_by_local_no',
            'start_ok_task_by_local_no' => 'AdminTestLog/start_ok_task_by_local_no',

            // 以上两个移动到AdminTest
            // 'start_task_by_local_no'    => 'AdminTest/start_task_by_local_no',
            // 'start_ok_task_by_local_no' => 'AdminTest/start_ok_task_by_local_no',


            // 'assert_change_data'     => 'AdminTestLog/assert_change_data',

            'logging_origin_data'    => 'AdminTestDetail/logging_origin_data',
            'logging_update_data'    => 'AdminTestDetail/logging_update_data',
            'show_test_assert_change' => 'AdminTestDetail/show_test_assert_change',
            'assert_change_is_passed' => 'AdminTestDetail/assert_change_is_passed',

            // 'logging_origin_data'    => 'AdminTestTask/logging_origin_data',
            // 'assert_change_data'     => 'AdminTestTask/assert_change_data',


            // 
            // 'start_ok_task_by_local_no' => 'AdminTestLog/start_ok_task_by_local_no',

            // 'createAdminTestBefore' => 'AdminTestBefore/createAdminTestBefore',








            // // new end




            
            // // 测试任务
            // 'task'                   => 'AdminTestTask/task',
            // 'addAdminTestTask'       => 'AdminTestTask/addAdminTestTask',
            // 'editAdminTestTask'      => 'AdminTestTask/editAdminTestTask',


            // 'local_task'             => 'AdminTestTask/local_task',
            // 'list'                   => 'AdminTestTask/list',
            
            // // 
            // 'group_task'             => 'AdminTestTask/group_task',
            
            // 'testDataList'           => 'AdminTestData/testDataList',
            // 'addAdminTestData'       => 'AdminTestData/addAdminTestData',
            
            
            
            
            // 'addTestDataForTask'     => 'AdminTestData/addTestDataForTask',
            // 'editAdminTestData'      => 'AdminTestData/editAdminTestData',
            
            // 'before_start_task'      => 'AdminTestData/before_start_task',
            
            // 'test_data_group_list'   => 'AdminTestDataGroup/test_data_group_list',
            
            // 'addAdminTestDataGroup'  => 'AdminTestDataGroup/addAdminTestDataGroup',
            // 'editAdminTestDataGroup' => 'AdminTestDataGroup/editAdminTestDataGroup',


        );
    }
}