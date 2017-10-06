<?php
namespace Plugins\AdminTest\Datamanager;
class AdminTestTaskDatamanager{

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

    private function _takeFormatData($type,$map,$p,$page_size,$order){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);



        foreach ($data as $key => $value) {


            $group_data[$key] = M('admin_test_data_group')->where(array('task_id'=>$value['id']))->select();

            $data[$key]['test_data_group'] = "";
            foreach ($group_data[$key] as $key2 => $value2) {
                # code...
                $data[$key]['test_data_group'] .= "<span class='label label-primary'>".$value2['title']."</span>".str_repeat("○", count(array_filter(explode(",", $value2['test_data_ids']))))."<br>";


            }
            // $data[$key]['test_data_group'];


            // $data[$key]['test_data_group'] = "<span class='label label-primary'>测试1</span>○○○○○○○○○○○○○○<br><span class='label label-primary'>测试2</span>○○○○○○○○○○○<br><span class='label label-primary'>测试3</span>○○○○○○○○○○○<br><span class='label label-primary'>测试4</span>○○○○○○○○○<br><span class='label label-primary'>测试5</span>○○○○○○○○○○○<br><span class='label label-primary'>测试6</span>○○○○○○<br><span class='label label-primary'>测试7</span>○○○○○○○○○○○○○";
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
            $list = M("admin_test_task")
                    ->field('*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_test_task")
                    ->field('id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}