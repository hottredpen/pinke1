<?php
namespace Admin\Datamanager;
/**
 * MenuDatamanager
 * 后台菜单数据管理对象类
 */
class AutoHandleDatamanager {

	public function getConfigData($name){

		switch ($name) {

            // 管理员
            case 'createAdmin':
                $thisConfig = array(
                    'name'        => '管理员',
                    'action'      => 'create',
                    'field'       => 'username,password,repassword,role_id,group,email,status',
                    'key'         => 11
                );
                break;
            case 'saveAdmin':
                $thisConfig = array(
                    'name'        => '管理员',
                    'action'      => 'save',
                    'field'       => 'id,username,password,repassword,role_id,group,email,status',
                    'key'         => 12
                );
                break;
            case 'ajaxAdmin':
                $thisConfig = array(
                    'name'        => '管理员',
                    'action'      => 'ajax',
                    'field'       => 'nofield', //不可直接设置,ajax操作不进入逻辑处理,需待优化 @todo hottredpen
                    'key'         => 12
                );
                break;
            case 'deleteAdmin':
                $thisConfig = array(
                    'name'        => '管理员',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;

            // 管理员组
            // createAdminGroup
            // saveAdminGroup
            case 'ajaxAdminGroup':
                $thisConfig = array(
                    'name'        => '管理员组',
                    'action'      => 'ajax',
                    'field'       => 'nofield', //不可直接设置,ajax操作不进入逻辑处理,需待优化 @todo hottredpen
                    'key'         => 12
                );
                break;
            case 'deleteAdminGroup':
                $thisConfig = array(
                    'name'        => '管理员组',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;



            // 菜单
			case 'createAdminMenu':
                $thisConfig = array(
                    'name'        => '菜单',
                    'action'      => 'create',
                    'field'       => 'name,pid,controller_name,action_name,data',
                    'key'         => 11
                );
				break;
			case 'saveAdminMenu':
                $thisConfig = array(
                    'name'        => '菜单',
                    'action'      => 'save',
                    'field'       => 'id,name,pid,controller_name,action_name,data',
                    'key'         => 12
                );
				break;
            case 'ajaxAdminMenu':
                $thisConfig = array(
                    'name'        => '菜单',
                    'action'      => 'ajax',
                    'field'       => 'id,name,pid,controller_name,action_name,data,ordid,status,top_pid',
                    'key'         => 12
                );
                break;
			case 'deleteAdminMenu':
                $thisConfig = array(
                    'name'        => '菜单',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
				break;

            // 日志模板
            case 'createAdminLogTpl':
                $thisConfig = array(
                    'name'        => '日志模板',
                    'action'      => 'create',
                    'field'       => 'title,model_scene,tpl,status',
                    'key'         => 11
                );
                break;
            case 'saveAdminLogTpl':
                $thisConfig = array(
                    'name'        => '日志模板',
                    'action'      => 'save',
                    'field'       => 'id,title,model_scene,tpl,status',
                    'key'         => 12
                );
                break;
            case 'ajaxAdminLogTpl':
                $thisConfig = array(
                    'name'        => '日志模板',
                    'action'      => 'ajax',
                    'field'       => 'id,status',
                    'key'         => 12
                );
                break;
            case 'deleteAdminLogTpl':
                $thisConfig = array(
                    'name'        => '日志模板',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;


            // Uploadconfig
            case 'createAdminUploadconfig':
                $thisConfig = array(
                    'name'        => '上传配置',
                    'action'      => 'create',
                    'field'       => 'name,from_module,typename,catid,maxsize,allowext,sub_type,sub_width,sub_height,scale_type,scale_width,scale_height,upload_return_type',
                    'key'         => 11
                );
                break;
            case 'saveAdminUploadconfig':
                $thisConfig = array(
                    'name'        => '上传配置',
                    'action'      => 'save',
                    'field'       => 'id,from_module,name,typename,catid,maxsize,allowext,sub_type,sub_width,sub_height,scale_type,scale_width,scale_height,upload_return_type',
                    'key'         => 12
                );
                break;
            case 'ajaxAdminUploadconfig':
                $thisConfig = array(
                    'name'        => '上传配置',
                    'action'      => 'ajax',
                    'field'       => 'id,from_module,name,typename,catid,maxsize,allowext,sub_type,sub_width,sub_height,scale_type,scale_width,scale_height,upload_return_type',
                    'key'         => 12
                );
                break;
            case 'deleteAdminUploadconfig':
                $thisConfig = array(
                    'name'        => '上传配置',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;


            // 配置
            case 'createAdminConfig':
                $thisConfig = array(
                    'name'        => '配置',
                    'action'      => 'create',
                    'field'       => 'name,title,module,type,value,tip,options,tips,status',
                    'key'         => 11
                );
                break;
            case 'saveAdminConfig':
                $thisConfig = array(
                    'name'        => '配置',
                    'action'      => 'save',
                    'field'       => 'id,name,title,module,type,value,tip,options,tips,status',
                    'key'         => 12
                );
                break;
            case 'ajaxAdminConfig':
                $thisConfig = array(
                    'name'        => '配置',
                    'action'      => 'ajax',
                    'field'       => 'status,item_group,sort',
                    'key'         => 12
                );
                break;
            case 'deleteAdminConfig':
                $thisConfig = array(
                    'name'        => '配置',
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