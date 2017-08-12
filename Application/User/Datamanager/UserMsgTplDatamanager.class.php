<?php
namespace User\Datamanager;
class UserMsgTplDatamanager{

 	function __construct() {

	}

    public function getData($p=1,$page_size=20,$map=array()){
        $data = $this->_takeFormatData("data",$map,$p,$page_size);
        return $data;
    }

    public function getInfo($id){
        $map['t.id'] = $id;
        $data = $this->_takeFormatData("data",$map,1,1);
        return $data[0];
    }

    public function getNum($map){
        $data = $this->_takeData("num",$map);
        return $data;
    }

    private function _takeFormatData($type,$map,$p,$page_size){
        $data = $this->_takeData("data",$map,$p,$page_size);
        return $data;
    }

    private function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" t.id desc "){
        $map = array();

        //合并覆盖
        $newmap = array_merge($map, $searchmap);

        $offset = ($p - 1) * $page_size;
        $offset = $offset < 0 ? 0 : $offset;

        if($type=="data"){
            $list = M("UserMsgTpl as t")
                    ->field('t.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
                    
            foreach ($list as $key => $value) {
                $list[$key]['content']   = htmlspecialchars_decode($value['content']);
            }
        }else{
            $list = M("UserMsgTpl as t")
                    ->field('t.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}