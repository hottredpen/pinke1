<?php
/**
 * 常用检测
 * 注意：函数内的is_name_pass 就是全局通用的，非全局通用的或者不常用的请直接写在model内，并用callback调用
 * 【全局通用】
 * is_[something]_pass     例如 is_notempty_pass,is_only_char_num_underline_pass
 * is_[name][attr]_pass    例如 is_title_length_pass,is_email_format_pass,is_phone_format_pass
 * 其他非此命名的的函数是辅助函数
 */
function is_notempty_pass($value){
	if($value==null || $value==""){
		return false;
	}
	return true;
}
function is_email_format_pass($email){
    if(preg_match("/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/",$email)){
        return true;
    }else{
        return false;
    }
}

function is_phone_format_pass($phone){
    if(preg_match("/^1[3|4|5|7|8]\d{9}$/",$phone)){
        return true;
    }else{
        return false;
    }
}
function is_qq_format_pass($qq){
    $pattern = '/^\d{5,12}$/' ;
    if (!preg_match($pattern, $qq)){
        return false;
    }
    return true;
}
function is_version_format_pass($version){
    $pattern = '/^\d{1,2}.\d{1,2}.\d{1,2}$/' ;
    if (!preg_match($pattern, $version)){
        return false;
    }
    return true;
}
function is_only_char_num_underline_pass($str){
    $pattern = "/^[A-Za-z0-9_]+$/u";//过滤非法字符
    $strArr  = str_split_unicode($str);
    foreach ($strArr as $key => $value) {
        if (!preg_match($pattern, $value)){
            return false;
        }
    }
    return true;
}
function is_only_chinese_char_num_pass($str){
    $pattern = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9\(\)\（\）]+$/u";//过滤非法字符
    $strArr  = str_split_unicode($str);
    foreach ($strArr as $key => $value) {
        if (!preg_match($pattern, $value)){
            return false;
        }
    }
    return true;
}
function is_plugins_pass($name){
    if(!F('all_plugins_data')){
        $all_plugins = M('admin_plugin')->select();
        foreach ($all_plugins as $key => $value) {
            $new_data[$value['name']] = $value;
        }
        F('all_plugins_data',$new_data);
    }
    $all_plugins_data = F('all_plugins_data');
    if($all_plugins_data[$name]){
        return true;
    }   
    return false;
}
/**
 * 通用的特殊符号过滤
 * todo 有待区分
 * is_title_safe_pass
 */
function is_filter_pass($str){
    $str     = str_replace(" ","",$str);//去掉空格后进行进行验证
    $str     = str_replace(",","",$str);//去掉","后进行进行验证
    $str     = str_replace("”","",$str);//去掉","后进行进行验证
    $str     = str_replace("“","",$str);//去掉","后进行进行验证
    $str     = str_replace("（","",$str);//去掉","后进行进行验证
    $str     = str_replace("）","",$str);//去掉","后进行进行验证
    $str     = str_replace("！","",$str);//去掉","后进行进行验证
    $str     = str_replace("？","",$str);//去掉","后进行进行验证
    $str     = str_replace("：","",$str);//去掉","后进行进行验证
    $str     = str_replace("《","",$str);//去掉","后进行进行验证
    $str     = str_replace("》","",$str);//去掉","后进行进行验证
    $str     = str_replace("、","",$str);//去掉","后进行进行验证
    $str     = str_replace("%","",$str);//去掉","后进行进行验证
    $pattern = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_，？-]+$/u";//过滤非法字符
    $strArr = str_split_unicode($str);
    foreach ($strArr as $key => $value) {
        if (!preg_match($pattern, $value)){
            return false;
        }
    }
    return true;
}
/**
 * 验证表格token
 */
function is_form_token_pass(){
    if(C('PK_TESTING')){
        return true;
    }
    if(C('PK_RESTFUL_TOKEN')){
        return true; // 目前直接返回true,后期加验证
    }
    $token       = $_POST['form_token'];
    $from_action = _util_replace_action_to_from_action(ACTION_NAME); // 获取当前动作的前动作
    $token_arr   = explode("-", $token);

    $token_salt  = $token_arr[0];
    $token_md5   = $token_arr[1];

    $ok_token    = md5(get_client_ip().$token_salt.strtolower(MODULE_NAME.CONTROLLER_NAME.$from_action));
    if($ok_token == $token_md5){
        return true;
    }else{
        return false;
    }
}
/*
 **********************************************************************
 *
 * 内部检测方法
 *
 **********************************************************************
 */
/**
 * 获取当前动作的前动作，为了验证表格的token是否正确
 */
function _util_replace_action_to_from_action($this_action){
    switch ($this_action) {
        // 一些特殊的转化（todo 降低耦合从此处剥离出去）
        case 'saveSetting':
            $from_action = "index"; // 保存系统配置
            break;
        case 'exportDatabase':
            $from_action = "exportConfirm"; // 备份数据
            break;
        case 'importDatabase':
            $from_action = "importConfirm"; // 还原数据
            break;

        default:
            $from_action =  preg_replace('/create([A-Z]{1}[\w]+)/', 'add$1', $this_action);
            $from_action =  preg_replace('/update([A-Z]{1}[\w]+)/', 'edit$1', $from_action);
            break;
    }
    // $from_action = "ddd"; // 测试错误token
    return $from_action;
}
function str_split_unicode($str, $l = 0) {
    if ($l > 0) {
        $ret = array();
        $len = mb_strlen($str, "UTF-8");
        for ($i = 0; $i < $len; $i += $l) {
            $ret[] = mb_substr($str, $i, $l, "UTF-8");
        }
        return $ret;
    }
    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
}

