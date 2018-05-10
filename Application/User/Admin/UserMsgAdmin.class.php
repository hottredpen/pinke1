<?php 
namespace User\Admin;

class UserMsgAdmin extends UserBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function index() {

    }

    public function sendform_sendMsg(){
        $id   = I('id',0,'intval');
        $info = D('User/User','Datamanager')->getInfo($id);
        // dump($info);
        $info['send_num']  = 1;
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改管理员')
                ->setFormItemCol_xs_sm_md_lg(array('md_r'=>9))
                ->setPostUrl(U('User/sendMsg'))
                ->addFormItem('username', 'div', '用户名')
                ->addFormItem('nickname', 'div', '昵称')
                ->addFormItem('phone', 'text', '手机号','',array(),array('attr'=>"disabled") )
                ->addFormItem('msgtpl_component_1', 'user_msg_tpl_box', '选择信件模板','注意：如果是自定义内容，请注意用语（短信网会审核未添加常用模板的信息）。如果是模板内容，除变量外,其他内容不要随意改。',array('trigger_from'=>'msgtpl_component_1','user_data'=>$info),array('selected_nav'=>'localmsg'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function sendMsg(){
        $id        = I('id',0,'intval');
        $info      = D('User/User','Datamanager')->getInfo($id);

        $msg_data = array_values(user_format_user_msg_tpl_box_post_data($_POST));
        $msg_data = $msg_data[0];


        $info['email'] = "hottredpen@126.com";
        // dump($msg_data);

        $all_is_ok = true;
        foreach ($msg_data['send_msg_type_arr'] as $key => $send_type) {
            # code...
            switch ($send_type) {
                // 站内信
                case 1:
                    $res  = D('User/UserMsg','Service')->sendLocalmsg($info['id'],$msg_data['msg_title'],$msg_data['localmsg_send_content']);
                    break;
                // 短信
                case 2:
                    $res  = D('User/UserMsg','Service')->sendSMS($info['id'],$info['phone'],$msg_data['msg_title'],$msg_data['sms_send_content']);
                    break;
                // 邮件
                case 3:
                    $res  = D('User/UserMsg','Service')->sendEmail($info['id'],$info['email'],$msg_data['msg_title'],$msg_data['email_send_content']);
                    break;
                default:
                    # code...
                    break;
            }
        }

        if($res['error']==0 && $res['info'] != ''){
            $this->pk_success($res['info']);
        }else{
            $this->pk_error($res['info']);
        }


    }




}