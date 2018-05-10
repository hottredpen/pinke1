<?php
namespace Plugins\AdminTest\Datamanager;
use Common\Datamanager\BaseDatamanager;

class AdminTestDatamanager extends BaseDatamanager{

    protected $replaceOptions = array(
        'id'            => 'at.id',
    );

    public function getLocalTestDataByModelName($module_name="Admin"){
        $list = glob(APP_PATH.ucfirst($module_name).'/Test/*.class.php');

        foreach ($list as $key => $value) {

            preg_match('/\/([a-zA-Z0-9]+)Test.class.php/',$value,$out);
            $model_name = $out[1];
            $class      = "\\{$module_name}\\Test\\".$model_name."Test";
            $obj        = new $class; // todo 不需要实例化
            $data       = $obj->getConfigData();
            $newlist[$key]['model_name']    = $model_name;
            $newlist[$key]['title']         = $data['title'];
            $newlist[$key]['handle_object'] = $data['handle_object'];
            $newlist[$key]['units_test']    = "";
            foreach ($data['units'] as $key2 => $value2) {
                $newlist[$key]['units_test'] .= "<div>".$value2['test_title'];
                foreach ($value2['test_data'] as $key3 => $value3) {

                    $newlist[$key]['units_test'] .= "<div style='margin-left:20px;'>{$key3}-最近测试情况------<a href='".U('Admin/AdminTest/addAdminTest',array('module_name'=>ucfirst($module_name),'model_name'=>$model_name,'sence_id'=>$key2,'group_id'=>$key3))."'>进入配置</a></div>";

                }    
                $newlist[$key]['units_test'] .= "</div>";
            }

            $newlist[$key]['groups_test'] = "";

            foreach ($data['groups'] as $key2 => $value2) {
                $newlist[$key]['groups_test'] .= "<div>".$value2['test_title'];

                foreach ($value2['test_data'] as $key3 => $value3) {

                    $newlist[$key]['groups_test'] .= "<div style='margin-left:20px;'>{$key3}-最近测试情况------<a href='".U('Admin/AdminTest/addAdminTest',array('module_name'=>ucfirst($module_name),'model_name'=>$model_name,'sence_id'=>$key2,'group_id'=>$key3))."'>进入配置</a></div>";

                }  
                
                $newlist[$key]['groups_test'] .= "</div>";
            }

        }
        return $newlist;

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
            $list = M("admin_test as at")
                    ->field('at.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_test as at")
                    ->field('at.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}