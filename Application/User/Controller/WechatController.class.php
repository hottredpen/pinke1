<?php
namespace User\Controller;
use Common\Controller\CommonBaseController;

class WechatController extends CommonBaseController {
	public function _initialize() {
        parent::_initialize();
    }

    public function index(){

    	// 暂时为展示用
        $menuid = I('menuid', 0, 'intval');
        if ($menuid > 0) {
            $left_menu = D("Admin/Menu","Datamanager")->getLeftAdminMenu_pid($menuid);
        } else {
            $left_menu = D("Admin/Menu","Datamanager")->getLeftOftenAdminMenu();
        }
        $this->assign('left_menu', $left_menu);


  		//$this->layoutdisplay();
    	// echo "<div>heihei</div>";
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增管理员')
                ->setPostUrl(U('Admin/addAdmin'))
                ->addFormItem('username', 'text', '管理员账号')
                ->addFormItem('password', 'password', '密码')
                ->addFormItem('repassword', 'password', '确认密码')
                ->addFormItem('email', 'text', '邮箱')
                // ->addFormItem('group', 'select', '所属分组', '所属分组', admin_local_admin_group_name())
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');

    }

    public function see(){
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增菜单')
                ->setPostUrl(U('Menu/addMenu'))
                // ->addFormItem('pid', 'select', '上级菜单','上级菜单',select_list_as_tree('menu', 'name', array(0=>'作为一级菜单'), 'id'))
                ->addFormItem('name', 'text', '菜单名称')
                ->addFormItem('module_name', 'text', '模块名')
                ->addFormItem('action_name', 'text', '方法名')
                ->addFormItem('data', 'text', '附加参数')
                ->addFormItem('remark', 'textarea', '备注')
                ->addFormItem('display', 'radio', '显示菜单','显示菜单',array(1=>'显示',0=>'不显示'))
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function login(){

    }


}
