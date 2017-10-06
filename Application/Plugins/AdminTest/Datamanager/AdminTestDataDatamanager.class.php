<?php
namespace Plugins\AdminTest\Datamanager;
class AdminTestDataDatamanager{

 	function __construct() {

	}
    public function getDataForStartTest($task_id=0){
        $data = M('admin_test_data_group')->where(array('task_id'=>$task_id))->select();
        foreach ($data as $key => $value) {
            if(trim($value['test_data_ids'],',') != ""){
                $data[$key]['test_data_ids_data'] = M('admin_test_data')->where(array('id'=>array('in',trim($value['test_data_ids'],','))))->select();
            }else{
                $data[$key]['test_data_ids_data'] = array();
            }
            
        }
        return $data;
    }
    public function getData($p=1,$page_size=20,$map=array(),$order=" id desc "){
        $data = $this->_takeFormatData("data",$map,$p,$page_size,$order);
        return $data;
    }

    public function getInfo($id=0){
        $map['id'] = $id;
        $data = $this->_takeFormatData("data",$map,1,1);
        return $data[0];
    }

    public function getNum($map=array()){
        $data = $this->_takeData("num",$map);
        return $data;
    }

    private function _takeFormatData($type="",$map=array(),$p=1,$page_size=20,$order=" id desc "){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);
        foreach ($data as $key => $value) {
            // 查找index_id个数(@todo left join)
            $index_data[$key] = M('admin_test_data_group')->where(array("task_id"=>$value['id'],"test_data_ids"=>array('like','%,'.$value['id'].',%')))->select();
            $data[$key]['test_data_group'] = "";
            foreach ($index_data[$key] as $key2 => $value2) {
                $data[$key]['test_data_group'] .="<span class='label label-primary'>".$value2['title']."</span><br>";
            }
        }
        return $data;
    }

    private function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" id desc "){
        $map = array();

        //合并覆盖
        $newmap = array_merge($map, $searchmap);

        $offset = ($p - 1) * $page_size;
        $offset = $offset < 0 ? 0 : $offset;

        if($type=="data"){
            $list = M("admin_test_data")
                    ->field('*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_test_data")
                    ->field('id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}