<?php 
namespace Plugins\AdminTest\Admin;
use Admin\Controller\BackController;

class AdminTestDataGroupAdmin extends BackController {

    protected function _initialize(){
        parent::_initialize();
    }

    public function test_data_group_list(){
        $task_id   = I('task_id',0,'intval');
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $map['task_id'] = $task_id;
        $order     = $this->getOrder();

        $data_list    = D('Plugins://AdminTest/AdminTestDataGroup','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Plugins://AdminTest/AdminTestDataGroup','Datamanager')->getNum($map);

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
                ->addTopButton('layer',array('data-url'=>U('admin/AdminTest/addAdminTestDataGroup',array('task_id'=>$task_id)),'data-width'=>"800px",'data-height'=>'520px','data-title'=>'新增-测试数据组'))
                ->addOrder('last_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '测试数据组标题')
                ->addTableColumn('test_data_ids', '测试数据组数据')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('layer',array('data-action'=>'editAdminTestDataGroup','data-width'=>"800px",'data-height'=>'520px','data-title'=>'编辑-测试内容'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdmin','data-itemname'=>'单元测试'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addAdminTestDataGroup(){
        $task_id  = I('task_id',0,'intval');

        $info['task_id'] = $task_id;
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增测试数据')
                ->setPostUrl(U('Admin/AdminTest/createAdminTestDataGroup'))
                ->addFormItem('title', 'text', '测试数据组标题')
                ->addFormItem('test_data_ids', 'text', '测试数据组数据')
                ->setFormData($info)
                ->addFormItem('task_id', 'hidden', 'task_id')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editAdminTestDataGroup(){
        $id  = I('id',0,'intval');

        $info     = D('Plugins://AdminTest/AdminTestDataGroup','Datamanager')->getInfo($id);
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改测试数据组')
                ->setPostUrl(U('Admin/AdminTest/saveAdminTestDataGroup'))
                ->addFormItem('title', 'text', '测试数据组标题')
                ->addFormItem('test_data_ids', 'text', '测试数据组数据')
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'id')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

}