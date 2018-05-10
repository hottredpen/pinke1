<?php
namespace Plugins\AdminTest\Datamanager;
use Common\Datamanager\BaseDatamanager;

class AdminTestDetailDatamanager extends BaseDatamanager{

    protected $replaceOptions = array(
        'id'            => 'atd.id',
    );

    public function getAssertData($test_id=0){
        $test_data   = M('admin_test')->where(array('id'=>$test_id))->find();
        $module_name = $test_data['module_name'];
        $model_name  = $test_data['model_name'];
        $sence_id    = $test_data['sence_id'];
        $group_id    = (int)$test_data['group_id'];
        $Plugins     = $test_data['is_plugin'] ? 'Plugins://' : '';
        $modelTest   = D($Plugins.$module_name.'/'.$model_name,'Test');

        C('admin_test.post_data',unserialize($test_data['success_post_data']));
        // 获取数据断言信息
        $assert_data = $modelTest->getAssertDataBySenceId($sence_id);
        // dump($assert_data);
        // exit();
        // 变量赋值
        $db_data = array();
        foreach ($assert_data as $key => $value) {
            // $value[0] ---- table
            list($table_name,$field_name,$update_info) = $value;
            if(!$db_data[$table_name]){
                $db_data[$table_name] = M('admin_test_detail')->where(array('test_id'=>$test_data['id'],'table_name'=>$table_name))->select();
            }
            $admin_test_detail_data[$key] = $db_data[$table_name];
            foreach ($admin_test_detail_data[$key] as $key2 => $value2) {
                // 测试前的数据
                if($value2['is_origin']){
                    C('admin_test_detail.origin_data',$value2);
                    if(strstr($field_name,'data.')){
                        list($_no_use,$_field_name)     = explode(".", $field_name);
                        $detail_unserialize_data[$key2] = unserialize($value2['data']);
                        $new_assert_data[$key]['origin_field_value'] = $detail_unserialize_data[$key2][$_field_name];
                    }else{
                        $new_assert_data[$key]['origin_field_value'] = $value2[$field_name];
                    }
                // 测试后的数据
                }else{
                    C('admin_test_detail.update_data',$value2);
                    if(strstr($field_name,'data.')){
                        list($_no_use,$_field_name)     = explode(".", $field_name);
                        $detail_unserialize_data[$key2] = unserialize($value2['data']);
                        $new_assert_data[$key]['update_field_value'] = $detail_unserialize_data[$key2][$_field_name];
                    }else{
                        $new_assert_data[$key]['update_field_value'] = $value2[$field_name];
                    }
                }
            }
            $update_rule[$key] = admin_test_format_field_var($update_info);
            $new_assert_data[$key]['table_name']       = $table_name;
            $new_assert_data[$key]['field_name']       = $field_name;
            $new_assert_data[$key]['update_rule_info'] = $update_rule[$key];
        }

        return $new_assert_data;

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
            $list = M("admin_test_detail as atd")
                    ->field('atd.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
        }else{
            $list = M("admin_test_detail as atd")
                    ->field('atd.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}