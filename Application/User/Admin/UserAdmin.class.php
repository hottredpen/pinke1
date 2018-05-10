<?php 
namespace User\Admin;

class UserAdmin extends UserBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function index() {
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('User/User','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('User/User','Datamanager')->getNum($map);

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('用户列表')
                ->setSearch(array('username'=>'用户名','nickname'=>'昵称','phone'=>'手机'),'',U('admin/user/index'))
                ->ajax_url(U('Weixin/ajaxWeixin'))
                ->addTopButton('layer',array('data-action'=>'addUser','data-width'=>"800px",'data-height'=>'630px','data-title'=>'新增-用户'))
                ->addOrder('id,username,sex,score,phone,reg_time,last_login_time,reg_from')
                ->addTimeFilter('reg_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('username', '用户名')
                ->addTableColumn('nickname', '昵称')
                ->addTableColumn('sex', '性别','function','common_sex_name')
                ->addTableColumn('score', '积分')
                ->addTableColumn('phone', '手机','function','user_format_phone_with_bind_info','is_bind_phone')
                ->addTableColumn('email', '邮箱','function','user_format_email_with_bind_info','is_bind_email')
                ->addTableColumn('qq', 'QQ','function','user_format_qq_with_bind_info','is_bind_qq')
                ->addTableColumn('reg_time', '注册时间','function','common_format_time')
                ->addTableColumn('reg_from', '来源','function','user_reg_from_name')
                ->addTableColumn('last_login_time', '最后登录时间','function','common_format_time')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('layer',array('title'=>'发消息','data-action'=>'sendform_sendMsg','data-width'=>"800px",'data-height'=>'630px','data-title'=>'发消息'))
                ->addRightButton('layer',array('data-action'=>'editUser','data-width'=>"800px",'data-height'=>'630px','data-title'=>'编辑-用户信息'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteUser','data-itemname'=>'用户'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addUser(){
        $info['sex'] = 1; // 默认值
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增用户')
                ->setPostUrl(U('User/createUser'))
                ->addFormItem('username', 'text', '用户名')
                ->addFormItem('password', 'password', '密码')
                ->addFormItem('nickname', 'text', '昵称')
                ->addFormItem('sex', 'radio', '性别','',common_sex_name())
                ->addFormItem('email', 'text', '邮箱')
                ->addFormItem('phone', 'text', '手机')
                ->addFormItem('qq', 'text', 'QQ')
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editUser(){
        $id   = I('id',0,'intval');
        $info = M('user')->where(array('id'=>$id))->find();

        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增管理员')
                ->setPostUrl(U('User/updateUser'))
                ->addFormItem('username', 'text', '用户名')
                ->addFormItem('password', 'password', '密码')
                ->addFormItem('nickname', 'text', '昵称')
                ->addFormItem('sex', 'radio', '性别','',common_sex_name())
                ->addFormItem('email', 'text', '邮箱')
                ->addFormItem('phone', 'text', '手机')
                ->addFormItem('qq', 'text', 'QQ')
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function createUser(){
        $UserAdminHandleObject = $this->visitor->UserAdminHandleObject();
        $res = $UserAdminHandleObject->createUser();
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info']);
        }else{
            $this->pk_error($res['info']);
        }
    }

    public function updateUser(){
        $UserAdminHandleObject = $this->visitor->UserAdminHandleObject();
        $res = $UserAdminHandleObject->updateUser();
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info']);
        }else{
            $this->pk_error($res['info']);
        }
    }
    public function deleteUser(){
        $UserAdminHandleObject = $this->visitor->UserAdminHandleObject();
        $res = $UserAdminHandleObject->deleteUser();
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info']);
        }else{
            $this->pk_error($res['info']);
        }
    }

}