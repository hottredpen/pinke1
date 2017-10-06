<?php
namespace Plugins\AdminTest\Datamanager;
/**
 * MenuDatamanager
 * 后台菜单数据管理对象类
 */
class AutoHandleDatamanager {

	public function getConfigData($name){

		switch ($name) {

            // 单元测试数据
            case 'createAdminTestDataGroup':
                $thisConfig = array(
                    'name'        => '测试数据',
                    'action'      => 'create',
                    'field'       => 'task_id,title,test_data_ids',
                    'key'         => 11
                );
                break;
            case 'saveAdminTestDataGroup':
                $thisConfig = array(
                    'name'        => '测试数据',
                    'action'      => 'save',
                    'field'       => 'id,task_id,title,test_data_ids',
                    'key'         => 12
                );
                break;
            case 'ajaxAdminTestDataGroup':
                $thisConfig = array(
                    'name'        => '测试数据',
                    'action'      => 'ajax',
                    'field'       => 'status',
                    'key'         => 12
                );
                break;
            case 'deleteAdminTestDataGroup':
                $thisConfig = array(
                    'name'        => '测试数据',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;


            // 单元测试数据
            case 'createAdminTestData':
                $thisConfig = array(
                    'name'        => '测试数据',
                    'action'      => 'create',
                    'field'       => 'task_id,title,field_name,field_success_value,field_error_value,assert',
                    'key'         => 11
                );
                break;
            case 'saveAdminTestData':
                $thisConfig = array(
                    'name'        => '测试数据',
                    'action'      => 'save',
                    'field'       => 'id,task_id,title,field_name,field_success_value,field_error_value,assert',
                    'key'         => 12
                );
                break;
            case 'ajaxAdminTestData':
                $thisConfig = array(
                    'name'        => '测试数据',
                    'action'      => 'ajax',
                    'field'       => 'status',
                    'key'         => 12
                );
                break;
            case 'deleteAdminTestData':
                $thisConfig = array(
                    'name'        => '测试数据',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;

            // 单元测试
            case 'createAdminTestTask':
                $thisConfig = array(
                    'name'        => '单元测试',
                    'action'      => 'create',
                    'field'       => 'title,module,controller,action,status',
                    'key'         => 11
                );
                break;
            case 'saveAdminTestTask':
                $thisConfig = array(
                    'name'        => '单元测试',
                    'action'      => 'save',
                    'field'       => 'id,title,module,controller,action,status',
                    'key'         => 12
                );
                break;
            case 'ajaxAdminTestTask':
                $thisConfig = array(
                    'name'        => '单元测试',
                    'action'      => 'ajax',
                    'field'       => 'status',
                    'key'         => 12
                );
                break;
            case 'deleteAdminTestTask':
                $thisConfig = array(
                    'name'        => '单元测试',
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