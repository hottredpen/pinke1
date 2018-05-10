<?php
namespace Admin\Datamanager;
use Common\Datamanager\BaseDatamanager;
class AdminIconDatamanager extends BaseDatamanager{

    protected $replaceOptions = array(
        'id'            => 'ai.id'
    );

    protected function _takeFormatData($type="",$map=array(),$p=1,$page_size=10,$order=" id desc "){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);
        foreach ($data as $key => $value) {
            $data[$key]['show_icon'] = $value['icon_show_pre']." ".$value['icon_value'];
        }
        return $data;
    }

    protected function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" id desc "){
        $searchmap = $this->replaceMap($searchmap);
        $order     = $this->replaceOrder($order);
        $offset    = $this->getOffset($p,$page_size);        

        $map = array();

        //合并覆盖
        if(count($searchmap) > 0){
            $newmap = array_merge($map, $searchmap);
        }else{
            $newmap = array();
        }

        if($type=="data"){
            $list = M("admin_icon as ai")
                    ->field('ai.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_icon as ai")
                    ->field('ai.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}