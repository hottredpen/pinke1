<?php
namespace Plugins\AdminTest\Service;

class AdminTestService{

    // 不做事务，因为test_id在变化
	public function loggingAdminTestDetailData($test_id=0,$is_origin=1){

        $test_data   = M('admin_test')->where(array('id'=>$test_id))->find();

        $module_name = $test_data['module_name'];
        $model_name  = $test_data['model_name'];
        $sence_id    = $test_data['sence_id'];
        $group_id    = (int)$test_data['group_id'];

        $Plugins     = $test_data['is_plugin'] ? 'Plugins://' : '';

        $modelTest   = D($Plugins.$module_name.'/'.$model_name,'Test');

        if($is_origin){
            $tables_arr  = $modelTest->getLogOriginData($sence_id);
        }else{
            $tables_arr  = $modelTest->getLogUpdateData($sence_id);
        }
        C('admin_test.post_data',unserialize($test_data['success_post_data']));
        $admin_test_detail_model = M('admin_test_detail');
        $all_is_ok = "true";
        foreach ($tables_arr as $key => $value) {
            list($table_name,$origin_id) = $value;
            $_id       = intval(admin_test_format_field_var($origin_id));
            $res[$key] = $this->_save_test_detail($admin_test_detail_model,$test_data['id'],$table_name,$_id,$is_origin);
            if(!$res[$key]){
                $all_is_ok .= "false";
            }
        }
        if($all_is_ok == "true"){
            return array('error'=>0,'info'=>'记录成功');
        }else{
            return array('error'=>1,'info'=>'记录失败');
        }
    }

    public function assertChangeIsPassed($test_id=0,$assert_index=0){
        $test_data   = M('admin_test')->where(array('id'=>$test_id))->find();

        $module_name = $test_data['module_name'];
        $model_name  = $test_data['model_name'];
        $sence_id    = $test_data['sence_id'];
        $group_id    = (int)$test_data['group_id'];

        $Plugins     = $test_data['is_plugin'] ? 'Plugins://' : '';

        $modelTest   = D($Plugins.$module_name.'/'.$model_name,'Test');

        // 获取数据断言信息
        $assert_data = $modelTest->getAssertDataBySenceId($sence_id);
        // 获取post的数据
        C('admin_test.post_data',unserialize($test_data['success_post_data']));
        // 变量赋值
        $db_data = array();
        $is_passed = false;
        foreach ($assert_data as $key => $value) {
            // $value[0] ---- table
            list($table_name,$field_name,$assert_info,$fun_type,$callback) = $value;
            if(!$db_data[$table_name]){
                $db_data[$table_name] = M('admin_test_detail')->where(array('test_id'=>$test_data['id'],'table_name'=>$table_name))->select();
            }
            $admin_test_detail_data[$key] = $db_data[$table_name];
            if($key == $assert_index){
                foreach ($admin_test_detail_data[$key] as $key2 => $value2) {
                    if($value2['is_origin']){
                        C('admin_test_detail.origin_data',$value2);
                    }else{
                        C('admin_test_detail.update_data',$value2);
                    }
                }
                if($fun_type == "function"){
                    $is_passed = $callback($field_name);
                }
            }
        }
        if($is_passed){
            return array('error'=>0,'info'=>'断言成功','assert_status'=>1);
        }else{
            return array('error'=>0,'info'=>'断言失败','assert_status'=>0);
        }
    }


    private function _save_test_detail($admin_test_detail_model,$test_id,$table_name,$id=0,$is_origin=1){

        $model              = M($table_name);
        $add['test_id']     = $test_id;
        $add['is_origin']   = $is_origin;
        $add['table_name']  = $table_name;
        $add['row_num']     = $model->count();
        $add['last_id']     = $model->max('id');
        $add['cur_id']      = $id;
        $data               = (int)$id > 0 ? $model->where(array('id'=>$add['cur_id']))->find() : array();
        $add['data']        = $data ? serialize($data) : serialize(array());

        $has = $admin_test_detail_model->where(array('test_id'=>$test_id,'table_name'=>$table_name,'is_origin'=>$is_origin))->find();

        if(!$has){
            $add['create_time'] = time();
            $res = $admin_test_detail_model->add($add);
        }else{
            $add['update_time'] = time();
            $res = $admin_test_detail_model->where(array('id'=>$has['id']))->save($add);
        }
        return $res;
    }


}