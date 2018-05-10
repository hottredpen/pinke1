<?php
namespace Admin\Admin;

class AdminConfigAdmin extends AdminBaseAdmin {
    
    public function _initialize() {
        parent::_initialize();
    }

    public function adminConfig(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Admin/AdminConfig','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Admin/AdminConfig','Datamanager')->getNum($map);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('配置管理列表')
                ->ajax_url(U('Admin/Admin/ajaxAdminConfig'))
                ->addFilter('module','function','admin_local_adminconfig_group')
                ->addTopButton('layer',array('data-action'=>'addAdminConfig','data-width'=>"800px",'data-height'=>'520px','data-title'=>'新增-配置'))
                ->addOrder('last_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('name', '标识')
                ->addTableColumn('title', '标题')
                ->addTableColumn('type', '类型')
                ->addTableColumn('module', '模块分组')
                ->addTableColumn('item_group', '表单集合','ajax_edit')
                ->addTableColumn('sort', '排序','ajax_edit')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('layer',array('data-action'=>'editAdminConfig','data-width'=>"800px",'data-height'=>'520px','data-title'=>'编辑-配置'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdminConfig','data-itemname'=>'配置'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addAdminConfig(){
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增配置')
                ->setPostUrl(U('Admin/Admin/createAdminConfig'))
                ->addFormItem('name', 'text', '配置标识')
                ->addFormItem('title', 'text', '标题')
                ->addFormItem('module', 'text', '所在模块','全局模块用Common')
                ->addFormItem('type', 'text', '类型')
                ->addFormItem('value', 'text', '默认值')
                ->addFormItem('tip', 'text', '提示语')
                ->addFormItem('item_group', 'text', 'item_group','用于表单的集合')
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
    public function editAdminConfig(){
        $id      = I('id',0,'intval');
        $info    = D('Admin/AdminConfig','Datamanager')->getInfo($id);
        $builder = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改管理员')
                ->setPostUrl(U('Admin/Admin/updateAdminConfig'))
                ->addFormItem('name', 'text', '配置标识')
                ->addFormItem('title', 'text', '标题')
                ->addFormItem('module', 'text', '所在模块','全局模块用Common')
                ->addFormItem('type', 'text', '类型')
                ->addFormItem('value', 'text', '默认值')
                ->addFormItem('tip', 'text', '提示语')
                ->addFormItem('item_group', 'text', 'item_group','用于表单的集合')
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
}