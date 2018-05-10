<?php
namespace Admin\Controller;

class UserController extends BackController {

    public function _initialize() {
        parent::_initialize();
        // 如果找不到方法，重新定义到新的Admin控制器
        $this->action_list =  array(
            // 用户
            'index'                        => 'User/index',
            'addUser'                      => 'User/addUser',
            'editUser'                     => 'User/editUser',
            'createUser'                   => 'User/createUser',
            'updateUser'                     => 'User/updateUser',
            'deleteUser'                   => 'User/deleteUser',

            // 发消息
            'sendform_sendMsg'             => 'UserMsg/sendform_sendMsg',
            'sendMsg'                      => 'UserMsg/sendMsg',

            // 信件模板
            'msg_tpl'                      => 'UserMsgTpl/msg_tpl', 
            'addform_UserMsgTpl'           => 'UserMsgTpl/addform_UserMsgTpl',
            'editform_UserMsgTpl'          => 'UserMsgTpl/editform_UserMsgTpl',
            'chooseform_user_msg_tpl'      => 'UserMsgTpl/chooseform_user_msg_tpl',
            'set_choose_user_msg_tpl'      => 'UserMsgTpl/set_choose_user_msg_tpl',

            // 记录
            'localmsg_log'                 => 'UserMsgLog/localmsg_log',
            'email_log'                    => 'UserMsgLog/email_log',
            'sms_log'                      => 'UserMsgLog/sms_log',



        );
    }




}