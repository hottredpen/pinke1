<?php
namespace Admin\Service;
class AdminModuleService {

    public function uninstallModuleByModuleId($module_id){
        $adminModuleModel = D('Admin/AdminModule');
        if(!$adminModuleModel->field('id')->create(array('id'=>$module_id),1103)){
            return array("error"=>1,"info"=>$adminModuleModel->getError());
        }
        $res_module  = $adminModuleModel->where(array('id'=>$module_id))->delete();
        if($res_module){
            return array('error'=>0,'info'=>'删除模块成功');
        }else{
            return array('error'=>1,'info'=>'删除模块失败');
        }
    }

    public function installLocalModule($module_name){
        // 获取模块类名
        $class = common_get_module_class($module_name);
        // 模块类不存在则跳过实例化
        if (!class_exists($class)) {
        	return array('error'=>1,'info'=>'模块的入口文件不存在！');
        }
        // 实例化模块
        $obj = new $class;
        if (!isset($obj->info) || empty($obj->info)) {
        	return array('error'=>1,'info'=>'模块信息缺失！');
        }
        // 模块未安装
        $info           = $obj->info; // 未安装的模块信息

        $Model               = D('Admin/AdminModule');
		
		$post['name']        = $info['name'];
		$post['title']       = $info['title'];
		$post['logo']        = $info['logo'];
		$post['icon']        = $info['icon'];
		$post['icon_color']  = $info['icon_color'];
		
		$post['description'] = $info['description'];
		$post['developer']   = $info['developer'];
		$post['author']      = $info['author'];
		$post['version']     = $info['version'];



        if(!$Model->field('name,title,logo,icon,icon_color,description,developer,author,version')->create($post,1101)){
            return array("error"=>1,"info"=>$Model->getError());
        }

        $res = $Model->add();
        if($res){
        	return array('error'=>0,'info'=>'安装成功','id'=>$res);
        }else{
        	return array('error'=>1,'info'=>'安装失败');
        }
    }


}