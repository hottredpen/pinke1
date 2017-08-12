<?php
namespace Plugins\AdminTest\Datamanager;
/**
 * MenuDatamanager
 * 后台菜单数据管理对象类
 */
class AutoHandleDatamanager {

	public function getConfigData($name){

		switch ($name) {

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