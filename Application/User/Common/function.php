<?php

function user_session_user_id(){
    
}

function user_reg_from_name($reg_from=null){
	$data[1] = '注册';
	$data[2] = '微信';
	$data[3] = '后台';
	if($reg_from > 0){
		return $data[$reg_from];
	}
	return $data;
}
function user_format_phone_with_bind_info($phone="",$param = array()){
    $is_bind = $param['is_bind_phone'];
    return user_format_contact_with_bind_info($phone,$is_bind);
}
function user_format_email_with_bind_info($email="",$param = array()){
    $is_bind = $param['is_bind_email'];
    return user_format_contact_with_bind_info($email,$is_bind);
}
function user_format_qq_with_bind_info($qq="",$param = array()){
    $is_bind = $param['is_bind_qq'];
    return user_format_contact_with_bind_info($qq,$is_bind);
}
function user_format_contact_with_bind_info($contact="",$is_bind){
    if($contact == ""){
        return "-";
    }
    $str = $contact;
    if($is_bind){
        $str .= "<span class='label label-success'>已绑定</span>";
    }else{
        $str .= "<span class='label label-warning'>未绑定</span>";
    }
    return $str;
}
function user_format_msg_type($msg_type){
    if($msg_type == 1){
        $str .= "<span class='label label-success'>系统</span>";
    }else{
        $str .= "<span class='label label-warning'>个人</span>";
    }
    return $str;
}
function user_format_msg_log_fname($fname,$param){
    $str = "";
    if($param['msg_type'] == 1){
        $str .= $param['adminname']."<a href='javascript:;'>管理员</a>";
    }else{
        $str .= $fname;
    }
    return $str;
}
function user_format_user_msg_tpl_box_post_data(){

    foreach ($_POST as $key => $value) {
        if(strstr($key,'_msg_title')){
            $trigger_box_name = str_replace("_msg_title", "", $key);
            $return_data[$trigger_box_name]['msg_title'] = $value;
        }
        if(strstr($key,'_user_msg_tpl_id')){
            $trigger_box_name = str_replace("_user_msg_tpl_id", "", $key);
            $return_data[$trigger_box_name]['tpl_id'] = $value;
        }
        if(strstr($key,'_sms_send_content')){
            $trigger_box_name = str_replace("_sms_send_content", "", $key);
            $return_data[$trigger_box_name]['sms_send_content'] = $value;
        }
        if(strstr($key,'_localmsg_send_content')){
            $trigger_box_name = str_replace("_localmsg_send_content", "", $key);
            $return_data[$trigger_box_name]['localmsg_send_content'] = $value;
        }
        if(strstr($key,'_email_send_content')){
            $trigger_box_name = str_replace("_email_send_content", "", $key);
            $return_data[$trigger_box_name]['email_send_content'] = $value;
        }
        if(strstr($key,'_send_msg_type_arr')){
            $trigger_box_name = str_replace("_send_msg_type_arr", "", $key);
            $return_data[$trigger_box_name]['send_msg_type_arr'] = $value;
        }
    }

    return $return_data;

}
function user_local_msg_tpl_module($msg_tpl_id=null){
    $data[1] = "系统通知";
    $data[2] = "财务通知";
    $data[3] = "活动通知";
    if($msg_tpl_id > 0){
        return $data[$msg_tpl_id];
    }
    return $data;
}