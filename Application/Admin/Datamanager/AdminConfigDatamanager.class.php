<?php
namespace Admin\Datamanager;
class AdminConfigDatamanager{

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

    public function getSettingFormDataByGroup($group="common"){
        $data = $this->getData(1,20,array('status'=>1,'module'=>$group),' sort desc');
        foreach ($data as $key => $value) {
            $item_list[$key]['name']  = $value['name'];
            $item_list[$key]['type']  = $value['type'];
            $item_list[$key]['title'] = $value['title'];
            $item_list[$key]['tip']   = $value['tip'];

            $info[$value['name']]  = $value['value'];
        }
        $info['group'] = $group;
        return array('item_list'=>$item_list,'info'=>$info);
    }

    public function getConfigData(){
        $data = M("admin_config")->where(array('status'=>1))->select();
        foreach ($data as $key=>$val) {
            $setting[$val['module'].'_'.$val['name']] = substr($val['value'], 0, 2) == 'a:' ? unserialize($val['value']) : $val['value'];
        }
        return $setting;
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
        $order  = $order == "" ? " id desc " : $order;

        if($type=="data"){
            $list = M("admin_config")
                    ->field('*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_config")
                    ->field('id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}