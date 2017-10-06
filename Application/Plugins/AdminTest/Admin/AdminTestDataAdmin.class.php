<?php 
namespace Plugins\AdminTest\Admin;
use Admin\Controller\BackController;

class AdminTestDataAdmin extends BackController {

    protected function _initialize(){
        parent::_initialize();
    }

    public function testDataList(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Plugins://AdminTest/AdminTestData','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Plugins://AdminTest/AdminTestData','Datamanager')->getNum($map);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('单元测试列表')
                ->SetTabNav(array(
                        array('title'=>'配置','href'=>U('Admin/AdminTest/setting')),
                        array('title'=>'测试数据','href'=>'javascript:;'),
                        array('title'=>'单元测试','href'=>U('Admin/AdminTest/task')),
                        array('title'=>'集成测试','href'=>U('Admin/AdminTest/group_task')),
                        array('title'=>'测试记录','href'=>U('Admin/AdminTest/task_log')),
                    ),1)
                ->setSearch(array('title'=>'测试标题'),'',U('admin/admin/index'))
                ->ajax_url(U('Admin/AdminTest/ajaxAdminTestTask'))
                ->addTopButton('layer',array('data-url'=>U('admin/AdminTest/addAdminTestData',array('task_id'=>'__id__')),'data-width'=>"800px",'data-height'=>'520px','data-title'=>'新增-单元测试'))
                ->addOrder('last_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '测试标题')
                ->addTableColumn('model_name', '所在模型')
                ->addTableColumn('test_data_group', '所在组')
                ->addTableColumn('field_name', '字段')
                ->addTableColumn('field_success_value', '正确值')
                ->addTableColumn('field_error_value', '错误值')
                ->addTableColumn('assert', '断言')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('layer',array('data-action'=>'editAdminTestData','data-width'=>"800px",'data-height'=>'520px','data-title'=>'编辑-测试内容'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdmin','data-itemname'=>'单元测试'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addAdminTestData(){
        $task_id  = I('task_id',0,'intval');

        $info['task_id'] = $task_id;
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增测试数据')
                ->setPostUrl(U('Admin/AdminTest/createAdminTestData'))
                ->addFormItem('title', 'text', '测试标题')
                ->addFormItem('field_name', 'text', '字段')
                ->addFormItem('field_success_value', 'text', '正确值')
                ->addFormItem('field_error_value', 'text', '错误值')
                ->addFormItem('assert', 'text', '断言内容')
                ->setFormData($info)
                ->addFormItem('task_id', 'hidden', 'task_id')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editAdminTestData(){
        $id  = I('id',0,'intval');

        $info     = D('Plugins://AdminTest/AdminTestData','Datamanager')->getInfo($id);
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改测试数据')
                ->setPostUrl(U('Admin/AdminTest/saveAdminTestData'))
                ->addFormItem('title', 'text', '测试标题')
                ->addFormItem('field_name', 'text', '字段')
                ->addFormItem('field_success_value', 'text', '正确值')
                ->addFormItem('field_error_value', 'text', '错误值')
                ->addFormItem('assert', 'text', '断言内容')
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'id')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }


    public function before_start_task(){
        $task_id   = I('id',0,'intval');
        $test_data = D('Plugins://AdminTest/AdminTestData','Datamanager')->getDataForStartTest($task_id);

        $this->assign('task_id',$task_id);
        $this->assign('test_data',$test_data);
        $this->theme('one')->admindisplay('Plugins://AdminTestData/testlist');
        exit();

        $info['task_id'] = $task_id;
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增测试数据')
                ->setPostUrl(U('Admin/Test/start_task'))
                ->addFormItem('eee', 'text', 'ddd')
                ->addFormItem('admin_test_doing_list', 'admin_test_doing_list', '测试内容','',array('test_data'=>$test_data))
                ->setFormData($info)
                ->addFormItem('task_id', 'hidden', 'task_id')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }



}