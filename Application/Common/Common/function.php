<?php
// 基础方法
require_once(APP_PATH.'Common/Common/utils_function.php');
// 过滤
require_once(APP_PATH.'Common/Common/filter_function.php');
// 常用检测
require_once(APP_PATH.'Common/Common/ispass_function.php');
// 转换方法
require_once(APP_PATH.'Common/Common/trans_function.php');
// 替换方法
require_once(APP_PATH.'Common/Common/replace_function.php');
// 将原始数据进行格式化的方法
require_once(APP_PATH.'Common/Common/format_function.php');
// seo
require_once(APP_PATH.'Common/Common/seo_function.php');
// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
function check_verify($code, $id = ''){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

function common_session_user_id(){
    // token 请求 
    $token  = I('token');
    $token  = str_replace(' ', '', $token); // 防止恶意的
    if( $token != '' ){
        $user_id = common_cache_last_token($token);
        return (int)$user_id;
    }
    if(isset($_SESSION['_admin_mock_user_info_']) && $_SESSION['_admin_mock_user_info_']['id'] > 0){
        return $_SESSION['_admin_mock_user_info_']['id'];
    }
    if(C('ENTER_FROM_API') > 0){
        return 0; // 接口过来都以token为唯一认证
    }else{
        return (int)$_SESSION['_common_user_info_']['id'];
    }
}
function common_cache_last_token($token){
    $user_id = (int)S($token);
    if($user_id == 0){
        // 如果后台清了一次缓存，则重新获取
        $db_user_id = M('user')->where(array('token'=>$token))->getField('id');
        if($db_user_id > 0){
            S($token,$db_user_id);
            S('user_id_'.$db_user_id.'_last_token',$token);
            return $db_user_id;
        }
        return 0;
    }
    if(APP_DEBUG){
        return (int)$user_id;
    }
    // 如果没有被清缓存直接从S中获取last_token进行比对
    $last_token = S('user_id_'.$user_id.'_last_token');
    if($last_token == $token){
        return (int)$user_id;
    }
    return 0;
}
// 通过以下方法进行事务时，可以在handle里面设置TRANS_START_METHOD,来对多个嵌套的事务进行统一的初始事务和终止事务的方法
// 使用如下：
// $storeModel = D('Store/Store');
// common_plus_start_trans(__METHOD__,$storeModel);
// 
//     C('TRANS_START_METHOD',__METHOD__); // 多层事务嵌套时，获取第一个common_plus_start_trans的__METHOD__，
//     C('TRANS_END_METHOD',__METHOD__);   // 多层事务嵌套时，获取最后一个common_plus_commit_trans或common_plus_rollback_trans的__METHOD__
// 
// 
function common_plus_start_trans($cur_method_name="",$model){
    if( null === C('TRANS_START_METHOD') ){
        C('TRANS_START_METHOD',$cur_method_name);
    }
    if(C('TRANS_START_METHOD') == $cur_method_name){
        // dump("相同方法,开始事务");
        $model->startTrans();
    }
}

function common_plus_commit_trans($cur_method_name="",$model){
    if( null === C('TRANS_END_METHOD') ){
        C('TRANS_END_METHOD',$cur_method_name);
    }
    if(C('TRANS_END_METHOD') == $cur_method_name){
        // dump("相同方法，提交");
        $model->commit();
    }
}

function common_plus_rollback_trans($cur_method_name="",$model){
    if( null === C('TRANS_END_METHOD') ){
        C('TRANS_END_METHOD',$cur_method_name);
        // dump('定义回滚来自:'.C('TRANS_END_METHOD'));
    }
    if(C('TRANS_END_METHOD') == $cur_method_name){
        // dump("相同方法，回滚");
        // dump(C('TRANS_END_METHOD'));
        $model->rollback();
    }
}




/**
* 对查询结果集进行排序
* @access public
* @param array $list 查询结果
* @param string $field 排序的字段名
* @param array $sortby 排序类型
* asc正向排序 desc逆向排序 nat自然排序
* @return array
*/
function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}
/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 * @author yangweijie <yangweijiester@gmail.com>
 */
function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
    if(is_array($tree)) {
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, $sortby='asc');
    }
    return $list;
}

function tree_add_pid($tree,$pid=0, $child = '_child', $order='id',&$index=0, &$list = array()){
    if(is_array($tree)) {
        foreach ($tree as $key => $value) {
        	$index++;
            $reffer = $value;
            $reffer['index'] = $index;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                $_pid = (int)$value[$order];
                tree_add_pid($value[$child],$_pid, $child, $order,$index, $list);
            }
            $reffer['pid'] = $pid;
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, $sortby='asc');
    }
    return $list;
}

/**
 * 模块特有的
 */
    


/**
 * 去掉HTML及空格，并截取一定的长度
 * @param  [string] $string 字符串
 * @param  [int]    $sublen  长度
 * @return [string] 字符串
 */
function cutstr_html($string, $sublen){
  $string = strip_tags($string);
  $string = preg_replace ('/ /is', '', $string);
  $string = preg_replace ('/ |　/is', '', $string);
  $string = preg_replace ('/&nbsp;/is', '', $string);
  
  preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);   
  if(count($t_string[0]) - 0 > $sublen){
        $string = join('', array_slice($t_string[0], 0, $sublen))."…";   
  }else{
        $string = join('', array_slice($t_string[0], 0, $sublen));
  }
  return $string;
 }

function remove_js_for_cpkbuild($str){
    $str = str_replace(".js","",$str);
    return $str;
}

/**
 * 获取所有数据并转换成一维数组
 * // todo 优化
 */
function select_list_as_tree($model, $title='title', $extra = null, $key = 'id',$order=' id asc ',$map=array()) {
    //获取列表
    $map['status'] = array('eq', 1);
    $list = M($model)->where($map)->order($order)->select();
    //转换成树状列表(非严格模式)
    $tree = new \Common\Util\Tree();
    $list = $tree->toFormatTree($list, $title);
    if ($extra) {
        $result = $extra;
    }
    //转换成一维数组
    foreach ($list as $val) {
        $result[$val[$key]] = $val[$title.'_format'];
    }
    return $result;
}




/**
 * 根据配置类型解析配置
 * @param  string $type  配置类型
 * @param  string  $value 配置值
 */
function parse_attr($value, $type = null) {
    switch ($type) {
        default: //解析"1:1\r\n2:3"格式字符串为数组
            $array = preg_split('/[\r\n]+/', trim($value, "\r\n"));
            if (strpos($value,':')) {
                $value  = array();
                foreach ($array as $val) {
                    list($k, $v) = explode(':', $val);
                    $value[$k]   = $v;
                }
            } else {
                $value = $array;
            }
            break;
    }
    return $value;
}
function session_guid(){
    if(!$_SESSION['guid']){
        $_SESSION['guid'] = guid();
    }
    return $_SESSION['guid'];
}

/**
 * 
 UUID是指在一台机器上生成的数字，它保证对在同一时空中的所有机器都是唯一的。
在ColdFusion中可以用CreateUUID()函数很简单的生成UUID，其格式为：xxxxxxxx-xxxx-xxxx- xxxxxxxxxxxxxxxx(8-4-4-16)，其中每个 x 是 0-9 或 a-f 范围内的一个十六进制的数字。而标准的UUID格式为：xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx (8-4-4-4-12)
ps:经测试基本每次值都不同
 *
 */
function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
        return $uuid;
    }
}
function common_sex_name($sex=0){
    // $data[0] = '未知';
    $data[1] = '男';
    $data[2] = '女';
    if($sex > 0){
        return $data[$sex];
    }   
    return $data;
}


function common_is_pjax(){
    if(I("_pjax","","trim") != ""){
        return true;
    }
    return false;
}

function common_is_without_layout(){
    if(I("_without_layout","","trim") != ""){
        return true;
    }
    return false;
}

function common_log_error($info,$extra){
    $add_data            = array();
    $add_data['info']    = $info;
    $add_data['extra']   = $extra;
    $add_data['addtime'] = time();
    M('common_unusual')->add($add_data);
}
function common_builder_echo_style($component_name){
    $component_name = str_replace("@", "/", $component_name);
    $component_name = str_replace("~", "/", $component_name);
    $file_path = "static/components/form_builder/".$component_name."/style.css";
    $load_num  = (int)C("builder_component_".$component_name."_load_num");
    if(file_exists($file_path) && (int)$load_num==0){
        $fp  = fopen($file_path,"r");
        $str = fread($fp,filesize($file_path));//指定读取大小，这里把整个文件内容读取出来
        C("builder_component_".$component_name."_load_num",++$load_num);
        return "<style>".str_replace("\r\n","<br />",$str)."</style>";
    }
    return "";
}

function common_builder_form_item_col_echo($data,$is_left=1){
    $str = " ";
    foreach ($data as $key => $value) {
        if($key == "label_class" && $is_left == 1){
            $str .= $value;
        }
        if($key == "input_class" && $is_left == 0){
            $str .= $value;
        }
        if($is_left == 1 && !strstr($key,"_l")){
            continue;
        }
        if($is_left == 0 && !strstr($key,"_r")){
            continue;
        }
        if(strstr($key,"md_")){
            $str .= " col-md-".$value." ";
        }
    }
    return $str;
}

function common_builder_form_item_col_echo_l($data){
    return common_builder_form_item_col_echo($data,1);
}

function common_builder_form_item_col_echo_r($data){
    return common_builder_form_item_col_echo($data,0);
}
function common_builder_id_name(){
    return "j_builder_".CONTROLLER_NAME."_".ACTION_NAME; // 为了pjax分页指定局部刷新位置
}
/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook,&$params=array()){
    \Think\Hook::listen($hook,$params);
}
function common_get_plugin_class($name){
    return "\\Plugins\\{$name}\\{$name}";
}
function common_get_module_class($name){
    return "\\{$name}\\{$name}";
}
/**
 * 将驼峰命名的插件名，获取最前面的单词作为模块名(首字母大写)
 * 所有模块名不能是多单词的
 */
function common_replace_plugin_name_to_module_name($plugin_name){
    $plugin_name_under = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $plugin_name));
    $str_arr = explode("_", $plugin_name_under);
    return ucfirst($str_arr[0]);
}

function common_replace_under_to_ucfirst($name_like_this){
    $name_arr = explode("_", $name_like_this);
    $newName = "";
    foreach ($name_arr as $key => $value) {
        $newName .= ucfirst($value);
    }
    return $newName;
}
function common_get_upload_config($typename=""){
    if(!F("common_get_upload_config")){
        $data = M("admin_uploadconfig")->select();
        foreach ($data as $key => $value) {
            $newdata[$value['typename']] = $value;
        }
        F("common_get_upload_config",$newdata);
    }
    $uploadconfig = F("common_get_upload_config");
    if($typename==""){
        return $uploadconfig;
    }else{
        return $uploadconfig[$typename];
    }
}
/**
 * todo 是否需要显示当前输出比例
 */
function common_upload_config_ext_info($typename){
    if($typename==""){
        return;
    }
    $config = common_get_upload_config($typename);
    if($config['allowext']==""){
        return "";
    }
    $ext_arr = explode("|", $config['allowext']);

    $allowext = implode(",", $ext_arr);
    $maxsize  = (int)$config['maxsize'];

    if(in_array("rar", $ext_arr)){
        $info = "(允许上传文件类型".$allowext."; 文件大小限制".$maxsize."M以内)";
    }else{
        $info = "(允许上传图片类型".$allowext."; 图片大小限制".$maxsize."M以内)";
    }
    
    if($typename=="bodybgimg"){
        $info = "(允许类型".$allowext."; 大小".$maxsize."M以内)";
    }
    if($typename=="realname"){
        $info = "(允许类型".$allowext."; 大小".$maxsize."M以内)";
    }


    return $info;
}
function common_local_plugins_local_file(){
    if(!F('common_local_plugins_local_file')){
        $dirs = array_map('basename', glob(C('PLUGIN_PATH').'*', GLOB_ONLYDIR));
        F('common_local_plugins_local_file',$dirs);            
    }
    return F('common_local_plugins_local_file');    
}
function common_local_modules_local_file(){
    if(!F('common_local_modules_local_file')){
        $bb = array_map('basename', glob(APP_PATH.'*', GLOB_ONLYDIR));
        $not_show_module = array('Runtime','Common','Api','Plugins','Install','Index');
        $dd = array_diff($bb,array_intersect($bb,$not_show_module));
        F('common_local_modules_local_file',$dd);            
    }
    return F('common_local_modules_local_file');
}
function common_local_components_local_file(){
    if(!F('common_local_components_local_file')){
        $dirs = array_map('basename', glob(C('COMPONENTS_PATH').'*', GLOB_ONLYDIR));
        F('common_local_components_local_file',$dirs);            
    }
    return F('common_local_components_local_file');    
}
/**
 * 替换模板中带有[admin_id|admin_local_admin_id_name]的内容
 */
function common_trans_log_tpl_by_self_func($tpl,$data){
    preg_match_all("/[.*?]*\[([a-z0-9_-]+)[|]+([a-z0-9_-]+)\][.*?]*/", $tpl, $all_match,PREG_SET_ORDER);
    for($i=0; $i< count($all_match); $i++){
        if(function_exists($all_match[$i][2])){
            $new[$i] = $all_match[$i][2]( $data[$all_match[$i][1]] );
            $tpl = str_replace($all_match[$i][0], $new[$i], $tpl);
        }
    }
    return $tpl;
}
function common_trans_log_tpl_by_before_data($tpl,$before_data){
    preg_match_all("/[.*?]*\[before_data\[([a-z0-9_-]+)\][|]*([a-z0-9_-]+)*\][.*?]*/", $tpl, $all_match,PREG_SET_ORDER);
    for($i=0; $i< count($all_match); $i++){
        if(function_exists($all_match[$i][2])){
            $new_value[$i] = $all_match[$i][2] ($before_data[$all_match[$i][1]]);
        }else{
            $new_value[$i] = $before_data[$all_match[$i][1]];
        }
        $tpl = str_replace($all_match[$i][0], $new_value[$i] , $tpl);
    }
    return $tpl;
}
function common_trans_log_tpl_by_after_data($tpl,$after_data){
    preg_match_all("/[.*?]*\[after_data\[([a-z0-9_-]+)\][|]*([a-z0-9_-]+)*\][.*?]*/", $tpl, $all_match,PREG_SET_ORDER);
    for($i=0; $i< count($all_match); $i++){
        if(function_exists($all_match[$i][2])){
            $new_value[$i] = $all_match[$i][2] ($after_data[$all_match[$i][1]]);
        }else{
            $new_value[$i] = $after_data[$all_match[$i][1]];
        }
        $tpl = str_replace($all_match[$i][0], $new_value[$i] , $tpl);
    }
    return $tpl;
}
/**
 * 产生一个指定长度的随机字符串,并返回给用户
 */
function  common_random_string($len = 6,$is_only_num=false) {
    if($is_only_num){
        $chars = array(
            "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z"
        );
    }else{
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
    }


    $charsLen = count($chars) - 1;
    // 将数组打乱 
    shuffle($chars);
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}
function errorinfo_explode_error_code($info="",$error=1){
    $code = $error == 0 ? 200 : -($error);
    preg_match_all("/[.*?]*@([a-z0-9_-]+)@/", $info, $all_match,PREG_SET_ORDER);
    for($i=0; $i< count($all_match); $i++){
        $info = str_replace($all_match[$i][0], "" , $info);
        $code = (int)$all_match[$i][1];
    }
    return array('info'=>$info,'code'=>$code);
}
function common_default($val,$default_val = "111"){
    if($val == '' || $val == null){
        return $default_val;
    }
    return $val;
}

if (!function_exists('mysql_get_server_info')) {
    function mysql_get_server_info(){
        return mysqli_get_server_info();
    }
}
