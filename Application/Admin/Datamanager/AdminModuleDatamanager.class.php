<?php
namespace Admin\Datamanager;
class AdminModuleDatamanager{

 	function __construct() {

	}

    public function getData($p=1,$page_size=20,$map=array(),$order){
        $data = $this->_takeFormatData("data",$map,$p,$page_size,$order);
        return $data;
    }

    public function getInfo($id){
        $map['id'] = $id;
        $data = $this->_takeFormatData("data",$map,1,1);
        return $data[0];
    }

    public function getNum($map){
        $data = $this->_takeData("num",$map);
        return $data;
    }

    public function getSettingTabByGroup($group="common"){
        $modules = $this->getData();
        array_unshift($modules,array('title'=>'通用','href'=>U('Admin/Setting/index',array('group'=>'common'))));
        
        $tab_list      = array();
        $cur_group_key = 0;
        foreach ($modules as $key => $value) {
            array_push($tab_list, array('title'=>$value['title'],'href'=>U('Admin/Setting/index',array('group'=>strtolower($value['name'])))));
            if($group == strtolower($value['name']) ){
                $cur_group_key = $key;
            }
        }
        return array('data'=>$tab_list,'key'=>$cur_group_key);
    }



    private function _takeFormatData($type,$map,$p,$page_size,$order){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);
        return $data;
    }

    private function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" id desc "){
        $map = array();

        //合并覆盖
        $newmap = array_merge($map, $searchmap);

        $offset = ($p - 1) * $page_size;
        $offset = $offset < 0 ? 0 : $offset;

        if($type=="data"){
            $list = M("admin_module")
                    ->field('*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_module")
                    ->field('id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}