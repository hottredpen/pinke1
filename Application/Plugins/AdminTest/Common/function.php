<?php
/**
 * post_data    为本次提交的数据
 * origin_data  为test_detail内的原始数据
 * update_data  为test_detail内的更新后数据
 */
function admin_test_plugins_config_test_last_id(){
    if(!C('test_last_id')){
        $last_data = M('admin_test')->order(' id desc ')->find();
        C('test_last_id',$last_data['id']);
    }
    return C('test_last_id');
}
function admin_test_is_not_has_var($var_name){
	if(!strstr($var_name,'{{')){
		return true;
	}else{
		return false;
	}
}
function admin_test_plugins_config_last_admin_test_log_data(){
    if(!C('last_admin_test_log_data')){
        $last_data = M('admin_test_log')->order(' id desc ')->find();
        C('last_admin_test_log_data',$last_data);
    }
    return C('last_admin_test_log_data');
}
/**
 * 替换所有字段里的变量
 * 前提是先给C('admin_test.post_data')等赋值
 *
 * @author hottredpen
 * @date   2018-04-02
 * @param  [type]     $var_name [description]
 * @return [type]               [description]
 */
function admin_test_format_field_var($var_name){
	if(!strstr($var_name,'{{')){
		return $var_name;
	}
	$post_data   = C('admin_test.post_data');
	$origin_data = C('admin_test_detail.origin_data');
	$update_data = C('admin_test_detail.update_data');
	// test_detail内data的替换
	if(strstr($var_name,'{{origin_data.data.')){
        preg_match_all("/{{origin_data.data.([a-zA-Z_]+)}}/", $var_name, $all_match,PREG_SET_ORDER);
        $_origin_data_data = unserialize($origin_data['data']);
        for($i=0; $i< count($all_match); $i++){
            $var_name = str_replace($all_match[$i][0], $_origin_data_data[$all_match[$i][1]], $var_name);
        }
	}else if (strstr($var_name,'{{origin_data.')){
        preg_match_all("/{{origin_data.([a-zA-Z_]+)}}/", $var_name, $all_match,PREG_SET_ORDER);
        for($i=0; $i< count($all_match); $i++){
            $var_name = str_replace($all_match[$i][0], $origin_data[$all_match[$i][1]], $var_name);
        }
	}
	if(strstr($var_name,'{{update_data.data.')){
        preg_match_all("/{{update_data.data.([a-zA-Z_]+)}}/", $var_name, $all_match,PREG_SET_ORDER);
        $_update_data_data = unserialize($update_data['data']);
        for($i=0; $i< count($all_match); $i++){
            $var_name = str_replace($all_match[$i][0], $_update_data_data[$all_match[$i][1]], $var_name);
        }
	}else if (strstr($var_name,'{{update_data.')){
        preg_match_all("/{{update_data.([a-zA-Z_]+)}}/", $var_name, $all_match,PREG_SET_ORDER);
        for($i=0; $i< count($all_match); $i++){
            $var_name = str_replace($all_match[$i][0], $update_data[$all_match[$i][1]], $var_name);
        }
	}
	// post_data只能是一维数据
	if(strstr($var_name,'{{post_data.')){
        preg_match_all("/{{post_data.([a-zA-Z_]+)}}/", $var_name, $all_match,PREG_SET_ORDER);
        for($i=0; $i< count($all_match); $i++){
            $var_name = str_replace($all_match[$i][0], $post_data[$all_match[$i][1]], $var_name);
        }
	}
	// 其他替换
	if(strstr($var_name,'{{log_data.handle_return_data.')){
		$log_all_data = admin_test_plugins_config_last_admin_test_log_data();
		$log_data = unserialize($log_all_data['handle_return_data']);
        preg_match_all("/{{log_data.handle_return_data.([a-zA-Z_]+)}}/", $var_name, $all_match,PREG_SET_ORDER);
        for($i=0; $i< count($all_match); $i++){
            $var_name = str_replace($all_match[$i][0], $log_data[$all_match[$i][1]], $var_name);
        }
	}
	if(strstr($var_name,'{{test.last_id}}')){
		$var_name = str_replace('{{test.last_id}}', admin_test_plugins_config_test_last_id(), $var_name);
	}
	return $var_name;
}
function admin_test_assert_null($field_name){
	$post_data   = C('admin_test.post_data');
	$origin_data = C('admin_test_detail.origin_data');
	$update_data = C('admin_test_detail.update_data');
    if(strstr($field_name,'.')){
        list($_data,$_field_name)  = explode(".", $field_name);
        $origin_unserialize_data   = unserialize($origin_data['data']);
        $update_unserialize_data   = unserialize($update_data['data']);
		if(null == $update_unserialize_data[$_field_name]){
			return true;
		}
		return false;
    }else{
    	$_field_name = $field_name;
		if(null == $update_data[$_field_name]){
			return true;
		}
		return false;
    }
}

// 断言的通用方法(如果是数组请自行序列化)
function admin_test_assert_eq($field_name){
	$post_data   = C('admin_test.post_data');
	$origin_data = C('admin_test_detail.origin_data');
	$update_data = C('admin_test_detail.update_data');
    if(strstr($field_name,'.')){
        list($_data,$_field_name)  = explode(".", $field_name);
        $origin_unserialize_data   = unserialize($origin_data['data']);
        $update_unserialize_data   = unserialize($update_data['data']);
		if($origin_unserialize_data[$_field_name] == $update_unserialize_data[$_field_name]){
			return true;
		}
		return false;
    }else{
    	$_field_name = $field_name;
		if($origin_data[$_field_name] == $update_data[$_field_name]){
			return true;
		}
		return false;
    }
}

function admin_test_assert_origin_to_update($field_name){
	$post_data   = C('admin_test.post_data');
	$origin_data = C('admin_test_detail.origin_data');
	$update_data = C('admin_test_detail.update_data');
    if(strstr($field_name,'.')){
        list($_data,$_field_name)  = explode(".", $field_name);
        $origin_unserialize_data   = unserialize($origin_data['data']);
        $update_unserialize_data   = unserialize($update_data['data']);
		if($origin_unserialize_data[$_field_name] != null &&  $update_unserialize_data[$_field_name] == $post_data[$_field_name]){
			return true;
		}
		return false;
    }else{
    	$_field_name = $field_name;
		if($origin_data[$_field_name] != null &&  $update_data[$_field_name] == $post_data[$_field_name]){
			return true;
		}
		return false;
    }
}

// 一般用于新增
function admin_test_assert_null_to_value($field_name){
	$post_data   = C('admin_test.post_data');
	$origin_data = C('admin_test_detail.origin_data');
	$update_data = C('admin_test_detail.update_data');
    if(strstr($field_name,'.')){
        list($_data,$_field_name)  = explode(".", $field_name);
        $origin_unserialize_data   = unserialize($origin_data['data']);
        $update_unserialize_data   = unserialize($update_data['data']);
		if($origin_unserialize_data[$_field_name] == null &&  $update_unserialize_data[$_field_name] == $post_data[$_field_name]){
			return true;
		}
		return false;
    }else{
    	$_field_name = $field_name;
		if($origin_data[$_field_name] == null &&  $origin_data[$_field_name] == $post_data[$_field_name]){
			return true;
		}
		return false;
    }
}


// 断言的通用方法(如果是数组请自行序列化)
function admin_test_assert_add_1($field_name){
	return admin_test_assert_add_sub($field_name,1);
}

function admin_test_assert_sub_1($field_name){
	return admin_test_assert_add_sub($field_name,-1);
}

function admin_test_assert_add_sub($field_name,$is_add = 1){
	$post_data   = C('admin_test.post_data');
	$origin_data = C('admin_test_detail.origin_data');
	$update_data = C('admin_test_detail.update_data');
    if(strstr($field_name,'.')){
        list($_data,$_field_name)  = explode(".", $field_name);
        $origin_unserialize_data   = unserialize($origin_data['data']);
        $update_unserialize_data   = unserialize($update_data['data']);
		if( ($origin_unserialize_data[$_field_name] + $is_add) == $update_unserialize_data[$_field_name]){
			return true;
		}
		return false;
    }else{
    	$_field_name = $field_name;
		if( ($origin_data[$_field_name] + $is_add ) == $update_data[$_field_name]){
			return true;
		}
		return false;
    }
}