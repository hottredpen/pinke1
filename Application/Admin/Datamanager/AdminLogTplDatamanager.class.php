<?php
namespace Admin\Datamanager;
class AdminLogTplDatamanager{

 	function __construct() {

	}

    public function replaceTplByData($data,$before_data,$after_data){
        $tpl_data = M('admin_log_tpl')->where(array('model_scene'=>$data['model'].'_'.$data['scene_id']))->find();
        if($tpl_data){
            // dump($tpl);
            $tpl = $tpl_data['tpl'];
            $tpl = str_replace("[admin_id]", $data['admin_id'], $tpl);
            $tpl = str_replace("[record_id]", $data['record_id'], $tpl);
            $tpl = common_trans_log_tpl_by_self_func($tpl,$data);
            $tpl = common_trans_log_tpl_by_before_data($tpl,$before_data);
            $tpl = common_trans_log_tpl_by_after_data($tpl,$after_data);
            // 替换
        }else{
            $tpl = "未找到模板";
        }
        return $tpl;
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
            $list = M("admin_log_tpl")
                    ->field('*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_log_tpl")
                    ->field('id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}