<?php 
namespace Admin\Admin;
use Common\Util\Tree;
class AdminGroupAdmin extends AdminBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function admingroup(){
        $data_list = D('Admin/AdminGroup')->where(array('status'=>array('egt', '0')))->order('sort asc, id asc')->select();
        $tree      = new Tree();
        $data_list = $tree->toFormatTree($data_list,'title');

        $right_button['no'][0]['title']     = '超级管理员无需操作';
        $right_button['no'][0]['attribute'] = 'class="label label-warning" href="#"';

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('管理组列表')
                ->ajax_url(U('Admin/Admin/ajaxAdminGroup'))
                ->addTopButton('custom',array('title'=>'新增','href'=>U('Admin/Admin/addAdminGroup')))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title_format', '标题')
                ->addTableColumn('icon', '图标', 'icon')
                ->addTableColumn('sort', '排序')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->addRightButton('custom',array('title'=>'修改','href'=>U('Admin/Admin/editAdminGroup',array('id'=>'__id__'))))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdminGroup','data-itemname'=>'管理组'))
                ->alterTableData(
                    array('key' => 'id', 'value' => '1'),
                    array('right_button' => $right_button)
                )
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');

    }

    public function addAdminGroup(){
        $tree      = new Tree();
        $menu_data = M('admin_menu')->where(array('status'=>1))->select();
        foreach ($menu_data as $key => $value) {
            $menu_data[$key]['title'] = $value['name'];
        }
        $all_module_menu_list = array();
        $all_module_menu_list['Admin']['title'] = "后台节点";
        $all_module_menu_list['Admin']['_child'] = $tree->list_to_tree($menu_data);


        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增配置')
                ->setPostUrl(U('Admin/Admin/createAdminGroup'))
                ->setBackUrl(U('Admin/Admin/admingroup'))
                ->addFormItem('pid', 'select', '上级部门','',select_list_as_tree('admin_group', 'title',array(0=>'作为一级部门'), 'id','id asc'))
                ->addFormItem('title', 'text', '部门名称')
                ->addFormItem('icon', 'text', '图标')
                ->addFormItem('menu_auth', 'menu_auth', '访问授权','',array('all_module_menu_list'=>$all_module_menu_list),array('item_col'=>array('md_l'=>0,'md_r'=>12)))
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->setItemToTab('pid,title,icon,status,',1,'角色信息')
                ->setItemToTab('menu_auth,',2,'访问授权')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
    public function editAdminGroup(){
        $id          = I('id',0,'intval');
        // info start
        $info      = M('admin_group')->find($id);
        $menu_data = M('admin_auth')->where(array('role_id'=>$id))->select();
        $menu_id_arr = array();
        foreach ($menu_data as $key => $value) {
            array_push($menu_id_arr, $value['menu_id']);
        }
        $info['menu_auth']['Admin'] = $menu_id_arr;


        $tree      = new Tree();
        $menu_data = M('admin_menu')->where(array('status'=>1))->select();
        foreach ($menu_data as $key => $value) {
            $menu_data[$key]['title'] = $value['name'];
        }
        $all_module_menu_list = array();
        $all_module_menu_list['Admin']['title'] = "后台节点";
        $all_module_menu_list['Admin']['_child'] = $tree->list_to_tree($menu_data);


        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增配置')
                ->setPostUrl(U('Admin/Admin/updateAdminGroup'))
                ->setBackUrl(U('Admin/Admin/admingroup'))
                ->addFormItem('pid', 'select', '上级部门','',select_list_as_tree('admin_group', 'title',array(0=>'作为一级部门'), 'id','id asc'))
                ->addFormItem('title', 'text', '部门名称')
                ->addFormItem('icon', 'text', '图标')
                ->addFormItem('menu_auth', 'menu_auth', '访问授权','',array('all_module_menu_list'=>$all_module_menu_list,'info_menu_auth'=>$info['menu_auth']),array('item_col'=>array('md_l'=>0,'md_r'=>12)))
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->setFormData($info)
                ->setItemToTab('pid,title,icon,status,id',1,'角色信息')
                ->setItemToTab('menu_auth',2,'访问授权')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');


    }
}