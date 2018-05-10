<?php
namespace Admin\Datamanager;
use Common\Datamanager\BaseDatamanager;
class AdminDatabaseDatamanager extends BaseDatamanager{

    protected $replaceOptions = array(
        'id'            => 'ad.id'
    );

    private function _takeFormatData($type,$map,$p,$page_size,$order){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);
        return $data;
    }

    private function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" id desc "){
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
            $list = M("admin_database as ad")
                    ->field('ad.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_database as ad")
                    ->field('ad.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}