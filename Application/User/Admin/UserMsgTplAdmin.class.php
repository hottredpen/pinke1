<?php 
namespace User\Admin;

class UserMsgTplAdmin extends UserBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function msg_tpl(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list = D('User/UserMsgTpl','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num  = D('User/UserMsgTpl','Datamanager')->getNum($map);

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('信件模板')
                ->addTopButton('layer',array('data-action'=>'addform_UserMsgTpl','data-width'=>"800px",'data-height'=>'630px','data-title'=>'新增-信件模板'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('tpl_module', '所属模块','function','user_local_msg_tpl_module')
                ->addTableColumn('title', '名称')
                ->addTableColumn('name', '标示')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('layer',array('data-action'=>'editform_UserMsgTpl','data-width'=>"900px",'data-height'=>'630px','data-title'=>'编辑-信件模板'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteUserMsgTpl','data-itemname'=>'信件模板'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');

    }

    public function addform_UserMsgTpl(){
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增信件模板')
                ->setPostUrl(U('User/addUserMsgTpl'))
                ->addFormItem('tpl_module', 'select', '所属模块','',user_local_msg_tpl_module())
                ->addFormItem('title', 'text', '名称')
                ->addFormItem('name', 'text', '标示','英文标示')
                ->addFormItem('content', 'ueditor', '模板',array(),array('width'=>'100%','height'=>'200px'))
                ->addFormItem('content_sms', 'textarea', '短信模板')
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editform_UserMsgTpl(){
        $id   = I('id',0,'intval');

        $info = D('User/UserMsgTpl','Datamanager')->getInfo($id);

        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改信件模板')
                ->setPostUrl(U('User/updateUserMsgTpl'))
                ->addFormItem('tpl_module', 'select', '所属模块','',user_local_msg_tpl_module())
                ->addFormItem('title', 'text', '名称')
                ->addFormItem('name', 'text', '标示','英文标示')
                ->addFormItem('content', 'ueditor', '模板',array(),array('width'=>'100%','height'=>'200px'))
                ->addFormItem('content_sms', 'textarea', '短信模板')
                ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用','0' => '禁用'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }


    public function chooseform_user_msg_tpl(){
        $builder_trigger_name = I('builder_trigger_name','','trim');
        $p         = I('p',1,'intval');
        $page_size = 1;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list = D('User/UserMsgTpl','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num  = D('User/UserMsgTpl','Datamanager')->getNum($map);


        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('信件列表')
                ->setCheckBoxType('radio')
                ->addTableColumn('title', '信件模板名称')
                ->addTableColumn('content_sms', '短信内容')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->setRadioBoxTrigger('J_choose_this_user_msg_tpl',' data-builder-trigger-name="'.$builder_trigger_name.'" ')
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function set_choose_user_msg_tpl(){
        $id      = I('id',0,'intval');
        $info    = D('User/UserMsgTpl','Datamanager')->getInfo($id);

        $returndata = array(
            'msg_title'        => $info['title'],
            'content_sms'      => $info['content_sms'],
            'content_localmsg' => $info['content'],
            'content_email'    => $info['content']
        );

        $this->pk_success('ok',$returndata);
    }


}