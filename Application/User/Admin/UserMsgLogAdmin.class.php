<?php 
namespace User\Admin;

class UserMsgLogAdmin extends UserBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function localmsg_log() {
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $map       = D('User/UserMsgLocalmsgLog','Datamanager')->replaceMap($map);
        $data_list = D('User/UserMsgLocalmsgLog','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num  = D('User/UserMsgLocalmsgLog','Datamanager')->getNum($map);

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('站内信记录列表')
                ->setSearch(array('adminname'=>'发送人(管理员)','fname'=>'发送人','tname'=>'接受人','title'=>'标题'),'',U('admin/user/localmsg_log'))
                ->addOrder('id,username,sex,score,phone,reg_time,last_login_time,reg_from')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('msg_type', '消息类型' ,'function','user_format_msg_type')
                ->addTableColumn('fname', '发送人/管理员','function','user_format_msg_log_fname','adminname,msg_type')
                ->addTableColumn('tname', '接受人')
                ->addTableColumn('title', '标题')
                ->addTableColumn('content', '内容')
                ->addTableColumn('addtime', '发送时间','function','common_format_time')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function email_log(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $map       = D('User/UserMsgEmailLog','Datamanager')->replaceMap($map);
        $data_list = D('User/UserMsgEmailLog','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num  = D('User/UserMsgEmailLog','Datamanager')->getNum($map);

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('邮件记录列表')
                ->setSearch(array('email'=>'邮箱','adminname'=>'发送人(管理员)','fname'=>'发送人','tname'=>'接受人','title'=>'标题'),'',U('admin/user/email_log'))
                ->addOrder('id,username,sex,score,phone,reg_time,last_login_time,reg_from')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('msg_type', '消息类型' ,'function','user_format_msg_type')
                ->addTableColumn('email', '邮箱')
                ->addTableColumn('fname', '发送人/管理员','function','user_format_msg_log_fname','adminname,msg_type')
                ->addTableColumn('tname', '接受人')
                ->addTableColumn('title', '标题')
                ->addTableColumn('content', '内容')
                ->addTableColumn('addtime', '发送时间','function','common_format_time')
                ->setTableDataList($data_list)
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function sms_log(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $map       = D('User/UserMsgSmsLog','Datamanager')->replaceMap($map);
        $data_list = D('User/UserMsgSmsLog','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num  = D('User/UserMsgSmsLog','Datamanager')->getNum($map);

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('用户列表')
                ->setSearch(array('phone'=>'手机号','adminname'=>'发送人(管理员)','fname'=>'发送人','tname'=>'接受人','title'=>'标题'),'',U('admin/user/sms_log'))
                ->addOrder('id,username,sex,score,phone,reg_time,last_login_time,reg_from')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('msg_type', '消息类型' ,'function','user_format_msg_type')
                ->addTableColumn('phone', '手机号')
                ->addTableColumn('fname', '发送人/管理员','function','user_format_msg_log_fname','adminname,msg_type')
                ->addTableColumn('tname', '接受人')
                ->addTableColumn('title', '标题')
                ->addTableColumn('content', '内容')
                ->addTableColumn('addtime', '发送时间','function','common_format_time')
                ->setTableDataList($data_list)
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }


}