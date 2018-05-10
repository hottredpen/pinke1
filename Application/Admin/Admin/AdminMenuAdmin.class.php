<?php
namespace Admin\Admin;

class AdminMenuAdmin extends AdminBaseAdmin {
    
    public function _initialize() {
        parent::_initialize();
    }

    public function menu() {
        $data_list = M("admin_menu")->order('ordid asc')->select();
        $tree      = new \Common\Util\Tree();
        $data_list = $tree->toFormatTree($data_list,'name');
        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('菜单列表')
                ->ajax_url(U('admin/Admin/ajaxAdminMenu'))
                ->addTopButton('layer',array('data-action'=>'addAdminMenu','data-width'=>"600px",'data-height'=>'650px','data-title'=>'新增-菜单'))
                ->setTabNav($tab_list, $group)
                ->addTableColumn('id', 'ID')
                ->addTableColumn('name_format', '菜单名称')
                ->addTableColumn('url', 'url')
                ->addTableColumn('top_pid', 'top_pid')
                ->addTableColumn('ordid', '排序','ajax_edit')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->addRightButton('layer',array('name'=>'add_sub_menu','title'=>'添加子菜单','data-action'=>'addAdminMenu','data-width'=>"600px",'data-height'=>'650px','data-title'=>'新增-菜单'))
                ->addRightButton('layer',array('data-action'=>'editAdminMenu','data-width'=>"600px",'data-height'=>'650px','data-title'=>'编辑-菜单'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdminMenu','data-itemname'=>'菜单'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addAdminMenu(){
        $id      = I('id',0,'intval');
        $info['pid'] = $id;
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增菜单')
                ->setPostUrl(U('admin/Admin/createAdminMenu'))
                ->addFormItem('pid', 'select', '上级菜单','上级菜单',select_list_as_tree('admin_menu', 'name', array(0=>'作为一级菜单'), 'id'))
                ->addFormItem('name', 'text', '菜单名称')
                ->addFormItem('module_name', 'text', '模块名','注意大小写')
                ->addFormItem('controller_name', 'text', '控制器名','注意大小写')
                ->addFormItem('action_name', 'text', '方法名')
                ->addFormItem('icon', 'icon', '图标')
                ->addFormItem('data', 'text', '附加参数')
                ->addFormItem('remark', 'textarea', '备注')
                ->addFormItem('display', 'radio', '显示菜单','显示菜单',array(1=>'显示',0=>'不显示'))
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editAdminMenu(){
        $id      = I('id',0,'intval');
        $info    = M('admin_menu')->where(array('id'=>$id))->find();
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增管理员')
                ->setPostUrl(U('admin/Admin/updateAdminMenu'))
                ->addFormItem('pid', 'select', '上级菜单','上级菜单',select_list_as_tree('admin_menu', 'name', array(0=>'作为一级菜单'), 'id'))
                ->addFormItem('name', 'text', '菜单名称')
                ->addFormItem('module_name', 'text', '模块名','注意大小写')
                ->addFormItem('controller_name', 'text', '控制器名','注意大小写')
                ->addFormItem('action_name', 'text', '方法名')
                ->addFormItem('data', 'text', '附加参数')
                ->addFormItem('remark', 'textarea', '备注')
                ->addFormItem('display', 'radio', '显示菜单','显示菜单',array(1=>'显示',0=>'不显示'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

}