<?php

namespace Admin\Controller;

class AdminLogTplController extends BackController{

    public function _initialize() {
        parent::_initialize();
    }

    public function index(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Admin/AdminLogTpl','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Admin/AdminLogTpl','Datamanager')->getNum($map);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')
                ->addTopButton('layer',array('data-action'=>'addAdminLogTpl','data-width'=>"800px",'data-height'=>'520px','data-title'=>'新增-日志模板'))
                ->ajax_url(U('Admin/AdminLogTpl/ajaxAdminLogTpl'))
                ->addOrder('last_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '标题')
                ->addTableColumn('model_scene', 'model_scene')
                ->addTableColumn('tpl', '模板')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('layer',array('data-action'=>'editAdminLogTpl','data-width'=>"800px",'data-height'=>'520px','data-title'=>'编辑-日志模板'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdminLogTpl','data-itemname'=>'日志模板'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addAdminLogTpl(){
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增日志模板')
                ->setPostUrl(U('Admin/AdminLogTpl/createAdminLogTpl'))
                ->addFormItem('title', 'text', '名称')
                ->addFormItem('model_scene', 'text', 'model_scene')
                ->addFormItem('tpl', 'textarea', '模板','删除时，请直接用[record_id]')
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
    public function editAdminLogTpl(){
        $id      = I('id',0,'intval');
        $info    = D('Admin/AdminLogTpl','Datamanager')->getInfo($id);
        $builder = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改日志模板')
                ->setPostUrl(U('Admin/AdminLogTpl/saveAdminLogTpl'))
                ->addFormItem('title', 'text', '名称')
                ->addFormItem('model_scene', 'text', 'model_scene')
                ->addFormItem('tpl', 'textarea', '模板','删除时，请直接用[record_id]')
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
}