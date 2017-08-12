<?php
namespace User\Datamanager;
class UserDatamanager{

 	function __construct() {

	}

    public function getData($p=1,$page_size=20,$map=array(),$order){
        $data = $this->_takeFormatData("data",$map,$p,$page_size,$order);
        return $data;
    }

    public function getInfo($id){
        $map['u.id'] = $id;
        $data = $this->_takeFormatData("data",$map,1,1);
        return $data[0];
    }

    public function getNum($map){
        $data = $this->_takeData("num",$map);
        return $data;
    }

    private function _takeFormatData($type,$map,$p,$page_size,$order){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);
        foreach ($data as $key => $value) {
            $_tmp[$key] = M('user_login')->where(array('user_id'=>$value['id']))->select();

            $data[$key]['is_bind_phone'] = 0;
            $data[$key]['is_bind_email'] = 0;
            $data[$key]['is_bind_qq']    = 0;
            foreach ($_tmp[$key] as $key2 => $value2) {
                if($value2['identity_type'] == "phone" && $value2['is_verified'] == 1){
                    $data[$key]['is_bind_phone'] = 1;
                }
                if($value2['identity_type'] == "email" && $value2['is_verified'] == 1){
                    $data[$key]['is_bind_email'] = 1;
                }
                if($value2['identity_type'] == "qq" && $value2['is_verified'] == 1){
                    $data[$key]['is_bind_qq']    = 1;
                }
            }
        }
        return $data;
    }

    private function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" u.id desc "){
        $map = array();

        //合并覆盖
        $newmap = array_merge($map, $searchmap);

        $offset = ($p - 1) * $page_size;
        $offset = $offset < 0 ? 0 : $offset;

        if($type=="data"){
            $list = M("User as u")
                    ->field('u.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("User as u")
                    ->field('u.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}