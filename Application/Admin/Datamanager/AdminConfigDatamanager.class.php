<?php
namespace Admin\Datamanager;
use Common\Datamanager\BaseDatamanager;
class AdminConfigDatamanager extends BaseDatamanager{

    protected $replaceOptions = array(
        'id'            => 'ac.id'
    );

    public function getSettingFormDataByGroup($group="common"){
        $data = $this->getData(1,20,array('status'=>1,'module'=>$group),' sort desc');
        foreach ($data as $key => $value) {
            $item_list[$key]['name']  = $value['name'];
            $item_list[$key]['type']  = $value['type'];
            $item_list[$key]['title'] = $value['title'];
            $item_list[$key]['tip']   = $value['tip'];

            $info[$value['name']]  = $value['value'];
        }
        $info['group'] = $group;
        return array('item_list'=>$item_list,'info'=>$info);
    }

    public function getConfigData(){
        $data = M("admin_config")->where(array('status'=>1))->select();
        foreach ($data as $key=>$val) {
            $setting[$val['module'].'_'.$val['name']] = substr($val['value'], 0, 2) == 'a:' ? unserialize($val['value']) : $val['value'];
        }
        return $setting;
    }

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
            $list = M("admin_config as ac")
                    ->field('ac.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_config as ac")
                    ->field('ac.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}