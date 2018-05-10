<?php
namespace Admin\Service;
class AdminMenuService {

    protected $tmp_data;

    public function deleteMenusByModuleId($module_id=0){
        $res = M('admin_menu')->where(array('module_id'=>$module_id,'is_system'=>0))->delete();
        if($res){
            return array('error'=>0,'info'=>'删除菜单成功');
        }else{
            return array('error'=>1,'info'=>'删除菜单失败');
        }
    }

    public function createMenusByLocalFile($module_name="",$module_id=0){

        $menuModel = D('Admin/AdminMenu');
        $menus = $this->_local_menus($module_name);

        $this->tmp_data['all_is_ok'] = "true";
        $this->tmp_data['menu_model'] = $menuModel;
        common_plus_start_trans(__METHOD__,$menuModel);// 开始事务
        // start add
        $this->_addMenus($menus,$module_name,0,$module_id); // 多层循环操作，无直接返回值

        if($this->tmp_data['all_is_ok'] == 'true'){
            common_plus_commit_trans(__METHOD__,$menuModel);
            return array('error'=>0,'info'=>'添加菜单成功');
        }else{
            common_plus_rollback_trans(__METHOD__,$menuModel);
            return array('error'=>1,'info'=>'添加菜单失败');
        }


    }

    private function _local_menus($module_name=""){
        $menus = array();
        if ($module_name != '' && is_file(APP_PATH. $module_name . '/menus.php')) {
            $menus = include APP_PATH. $module_name . '/menus.php'; // 从菜单文件获取
        }
        return $menus;
    }


    private function _addMenus($menus = array(), $module_name = '', $pid = 0,$module_id=0){
        foreach ($menus as $key => $value) {
            $data[$key]['module_id']       = $module_id;
            $data[$key]['name']            = $value['title'];
            $data[$key]['pid']             = $pid;
            $data[$key]['module_name']     = $module_name;
            $data[$key]['controller_name'] = $module_name;
            $data[$key]['url']             = $value['url'];

            $Model = $this->tmp_data['menu_model'];
            if(!$Model->field('module_id,name,pid,module_name,controller_name,url')->create($data[$key],1101)){
                return array("error"=>1,"info"=>$Model->getError());
            }
            $res[$key] = $Model->add();
            if(!$res[$key]){
                $this->tmp_data['all_is_ok'] = "false";
            }else{
                if (isset($value['child'])) {
                    $this->_addMenus($value['child'], $module_name, $res[$key],$module_id);
                }
            }
        }
    }

}