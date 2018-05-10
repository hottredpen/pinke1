<?php
namespace Admin\Datamanager;
use Common\Datamanager\BaseDatamanager;
class AdminDatamanager extends BaseDatamanager{

    protected $replaceOptions = array(
        'id'            => 'a.id'
    );

    protected function _takeFormatData($type="",$map=array(),$p=1,$page_size=10,$order=" id desc "){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);
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
            $list = M("admin as a")
                    ->field('a.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin as a")
                    ->field('a.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}