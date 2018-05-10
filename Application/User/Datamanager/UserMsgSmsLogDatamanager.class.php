<?php
namespace User\Datamanager;
class UserMsgSmsLogDatamanager{

 	function __construct() {

	}

    public function getData($p=1,$page_size=20,$map=array()){
        $data = $this->_takeFormatData("data",$map,$p,$page_size);
        return $data;
    }

    public function getInfo($id){
        $map['l.id'] = $id;
        $data = $this->_takeFormatData("data",$map,1,1);
        return $data[0];
    }

    public function getNum($map){
        $data = $this->_takeData("num",$map);
        return $data;
    }

    public function replaceMap($map){
        $replace_arr = array(
            'adminname' => 'ad.username',
            'fname'     => 'uf.username',
            'tname'     => 'ut.username',
            'phone'     => 'l.phone'
        );
        $newmap = common_replace_dbpre_name_for_leftjoin_map($map,$replace_arr);
        return $newmap;
    }


    private function _takeFormatData($type,$map,$p,$page_size){
        $data = $this->_takeData("data",$map,$p,$page_size);
        return $data;
    }



    private function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" l.id desc "){
        $map = array();

        //合并覆盖
        $newmap = array_merge($map, $searchmap);

        $offset = ($p - 1) * $page_size;
        $offset = $offset < 0 ? 0 : $offset;

        if($type=="data"){
            $list = M("user_msg_sms_log as l")
                    ->join('left join '.C('DB_PREFIX').'user AS uf on l.fuid=uf.id')
                    ->join('left join '.C('DB_PREFIX').'user AS ut on l.tuid=ut.id')
                    ->join('left join '.C('DB_PREFIX').'admin AS ad on l.admin_id=ad.id')
                    ->field('l.*,uf.username AS fname,ut.username As tname,ad.username As adminname')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
                    
        }else{
            $list = M("user_msg_sms_log as l")
                    ->join('left join '.C('DB_PREFIX').'user AS uf on l.fuid=uf.id')
                    ->join('left join '.C('DB_PREFIX').'user AS ut on l.tuid=ut.id')
                    ->join('left join '.C('DB_PREFIX').'admin AS ad on l.admin_id=ad.id')
                    ->field('l.id,uf.username AS fname,ut.username As tname,ad.username As adminname')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}