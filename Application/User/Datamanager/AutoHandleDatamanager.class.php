<?php
namespace User\Datamanager;
/**
 * 
 */
class AutoHandleDatamanager {

	public function getConfigData($name){

		switch ($name) {

            // 会员用户
            case 'addUser':
                $thisConfig = array(
                    'name'        => '会员用户',
                    'action'      => 'add',
                    'field'       => 'nickname,username,password,repassword,email,phone,qq',
                    'key'         => 11
                );
                break;
            case 'updateUser':
                $thisConfig = array(
                    'name'        => '会员用户',
                    'action'      => 'save',
                    'field'       => 'id,nickname,username,password,repassword,email,phone,qq',
                    'key'         => 12
                );
                break;
            case 'ajaxUser':
                $thisConfig = array(
                    'name'        => '会员用户',
                    'action'      => 'ajax',
                    'field'       => 'id,nickname',
                    'key'         => 12
                );
                break;
            case 'deleteUser':
                $thisConfig = array(
                    'name'        => '会员用户',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;

            // 信件模板
            case 'addUserMsgTpl':
                $thisConfig = array(
                    'name'        => '信件模板',
                    'action'      => 'add',
                    'field'       => 'tpl_module,title,name,content,content_sms,status',
                    'key'         => 11
                );
                break;
            case 'updateUserMsgTpl':
                $thisConfig = array(
                    'name'        => '信件模板',
                    'action'      => 'save',
                    'field'       => 'id,tpl_module,title,name,content,content_sms,status',
                    'key'         => 12
                );
                break;
            case 'ajaxUserMsgTpl':
                $thisConfig = array(
                    'name'        => '信件模板',
                    'action'      => 'ajax',
                    'field'       => 'status',
                    'key'         => 12
                );
                break;
            case 'deleteUserMsgTpl':
                $thisConfig = array(
                    'name'        => '信件模板',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;


			default:
                // 没有找到
				return false;
				break;
		}
        $thisConfig['model'] = str_replace($thisConfig['action'], "" , $name);
		return $thisConfig;
	}

}