<?php
namespace Admin\Datamanager;
/**
 * AdminMenuDatamanager
 * 后台菜单数据管理对象类
 */
class AdminMenuDatamanager {


	public function getAuthDataByUrl($current_url){
		if(!F('getAuthDataByUrl_'.$current_url)){
	        $menuIdResult = M('admin_menu')->field("id,top_pid")->where(array('url'=>$current_url))->select();
	        $menuids_arr = array();
	        foreach($menuIdResult as $key=>$value){
	            array_push($menuids_arr, $value['id']);
	            if($key == 0){
	                $top_menu_id = $value['top_pid'];
	            }
	        }
	        // 组装数据
	        $data['menuids_arr']    = $menuids_arr;
	        $data['top_menu_name']  = M('admin_menu')->where(array('id'=>$top_menu_id))->getField('controller_name');
	        F('getAuthDataByUrl_'.$current_url,$data);
		}
		$data = F('getAuthDataByUrl_'.$current_url);
		return $data;
	}

	public function getAllMenu(){
		if(!F("AllAdminMenu")){
			$top_menus = $this->getTopAdminMenu();
			foreach ($top_menus as $top_key => $top_value) {
				$map['pid']      = (int)$top_value['id'];
				$map['display']  = 1;
				$map['status']   = 1;
		        $menus = M("admin_menu")->where($map)->order('ordid')->select();
	        	foreach ($menus as $key => $value) {
					$map['pid']                      = $value['id'];
					$new_menus[$top_key.$key]        = $value;
					$new_menus[$top_key.$key]['top_tab_name'] = $top_value['controller_name'];
					$new_menus[$top_key.$key]['sub'] = M("admin_menu")->where($map)->order('ordid')->select();
	        	}
			}
	        F("AllAdminMenu",$new_menus);
		}
		$menus = F("AllAdminMenu");
        return $menus;
	}

	public function getTopAdminMenu(){
		if(!F("getTopAdminMenu")){
			$map['pid']      = 0;
			$map['display']  = 1;
			$map['status']   = 1;
	        $menus = M("admin_menu")->where($map)->order('ordid')->select();
	        F("getTopAdminMenu",$menus);
		}
		$menus = F("getTopAdminMenu");
        return $menus;
	}
}