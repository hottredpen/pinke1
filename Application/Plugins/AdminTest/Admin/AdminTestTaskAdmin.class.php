<?php 
namespace Plugins\AdminTest\Admin;

class AdminTestTaskAdmin extends AdminTestBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function task(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Plugins://AdminTest/AdminTestTask','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Plugins://AdminTest/AdminTestTask','Datamanager')->getNum($map);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('单元测试列表')
                ->SetTabNav(array(
                        array('title'=>'配置','href'=>U('Admin/AdminTest/setting')),
                        array('title'=>'测试数据','href'=>U('Admin/AdminTest/testDataList')),
                        array('title'=>'单元测试','href'=>'javascript:;'),
                        array('title'=>'集成测试','href'=>U('Admin/AdminTest/group_task')),
                        array('title'=>'测试记录','href'=>U('Admin/AdminTest/task_log')),
                    ),2)
                ->setSearch(array('title'=>'测试标题'),'',U('admin/admin/index'))
                ->ajax_url(U('Admin/AdminTest/ajaxAdminTestTask'))
                ->addTopButton('layer',array('data-action'=>'addAdminTestTask','data-width'=>"800px",'data-height'=>'520px','data-title'=>'新增-单元测试'))
                ->addOrder('last_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '测试标题')
                ->addTableColumn('model_name', '所属模型')
                ->addTableColumn('handle_object', '操作对象')
                ->addTableColumn('action', '行为')
                ->addTableColumn('test_data_group', '数据组')
                ->addTableColumn('time', '最后测试时间')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('custom',array('title'=>'添加数据组','href'=>U('admin/AdminTest/test_data_group_list',array('task_id'=>'__id__'))))
                ->addRightButton('custom',array('title'=>'准备测试','href'=>U('admin/AdminTest/before_start_task',array('id'=>'__id__'))))
                ->addRightButton('layer',array('data-action'=>'editAdminTestTask','data-width'=>"800px",'data-height'=>'520px','data-title'=>'编辑-单元测试'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdmin','data-itemname'=>'单元测试'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addAdminTestTask(){
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增单元测试')
                ->setPostUrl(U('AdminTest/createAdminTestTask'))
                ->addFormItem('title', 'text', '测试标题')
                ->addFormItem('module', 'text', 'module')
                ->addFormItem('controller', 'text', 'controller')
                ->addFormItem('action', 'text', 'action')
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editAdminTestTask(){
        $id      = I('id',0,'intval');
        $info    = D('Plugins://AdminTest/AdminTestTask','Datamanager')->getInfo($id);
        $builder = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改单元测试')
                ->setPostUrl(U('AdminTest/saveAdminTestTask'))
                ->addFormItem('title', 'text', '测试标题')
                ->addFormItem('module', 'text', 'module')
                ->addFormItem('controller', 'text', 'controller')
                ->addFormItem('action', 'text', 'action')
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }




    public function group_task(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Plugins://AdminTest/AdminTestTask','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Plugins://AdminTest/AdminTestTask','Datamanager')->getNum($map);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('管理员列表')
                ->SetTabNav(array(
                        array('title'=>'配置','href'=>U('Admin/AdminTest/setting')),
                        array('title'=>'单元测试','href'=>U('Admin/AdminTest/task')),
                        array('title'=>'集成测试','href'=>'javascript:;'),
                        array('title'=>'测试记录','href'=>U('Admin/AdminTest/task_log')),
                    ),2)

        
                ->setSearch(array('title'=>'测试标题'),'',U('admin/admin/index'))
                ->ajax_url(U('Admin/ajaxAdmin'))
                ->addTopButton('layer',array('data-action'=>'addAdmin','data-width'=>"800px",'data-height'=>'520px','data-title'=>'新增-管理员'))
                ->addOrder('last_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('username', '管理员账号')
                ->addTableColumn('group', '所属分组','function','admin_local_admin_group_name')
                ->addTableColumn('last_time', '最后登录时间','function','common_format_time')
                ->addTableColumn('last_ip', '最后登录IP')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('layer',array('data-action'=>'editAdmin','data-width'=>"800px",'data-height'=>'520px','data-title'=>'编辑-管理员'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdmin','data-itemname'=>'管理员'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }


}