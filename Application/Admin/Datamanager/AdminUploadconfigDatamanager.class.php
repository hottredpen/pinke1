<?php
namespace Admin\Datamanager;
use Common\Datamanager\BaseDatamanager;
// todo 待优化
class AdminUploadConfigDatamanager extends BaseDatamanager{

    protected $replaceOptions = array(
        'id'            => 'au.id'
    );

    protected function _takeFormatData($type="data",$map=array(),$p=1,$page_size=20,$order=" id desc "){
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
            $list = M("admin_uploadconfig as au")
                    ->field('au.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_uploadconfig as au")
                    ->field('au.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}