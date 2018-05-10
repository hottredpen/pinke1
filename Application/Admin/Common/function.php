<?php
function admin_session_admin_id(){
    $admin   = session("admin");
    if($_SESSION['IS_TEST_VISITOR_ID'] > 0 ){
        // @todo 添加token检测,防止恶意修改
        return (int)$_SESSION['IS_TEST_VISITOR_ID'];
    }else{
        return (int)$admin['id'];
    }
}

// admin_local_upload_return_type_name
function admin_local_upload_return_type_name($id=null){
    $data[-1] = '不保留原图（裁剪）返回';
    $data[0] = '原图';
    $data[1] = '裁剪图';
    $data[2] = '缩放图';
    if($id != null){
        return $data[$id];
    }
    return $data;
}
// admin_local_upload_sub_type_name
function admin_local_upload_sub_type_name($id=null){
    $data[0] = '不裁剪';
    $data[1] = '居中裁剪';
    $data[2] = '左上角裁剪';
    $data[3] = '右上角裁剪';
    if($id != null){
        return $data[$id];
    }
    return $data;
}
// admin_local_upload_scale_type_name
function admin_local_upload_scale_type_name($id=null){
    $data[0] = '不缩放';
    $data[1] = '缩放不填充';
    $data[2] = '缩放填充';
    $data[3] = '变形缩放';
    if($id != null){
        return $data[$id];
    }
    return $data;
}

function admin_local_admin_group_name($admin_group=0){
    if(!F('admin_local_admin_group_name')){
        $data = M('admin_group')->getField('id,title');
        F('admin_local_admin_group_name',$data);
    }
    $data = F('admin_local_admin_group_name');
    if($admin_group > 0){
        return $data[$admin_group];
    }
    return $data;
}

function admin_local_admin_id_name($admin_id=null){
    if(!F('admin_local_admin_id_name')){
        $data = M('admin')->getField('id,username');
        F('admin_local_admin_id_name',$data);
    }
    if($admin_id == 0){
        return "系统";
    }
    $data = F('admin_local_admin_id_name');
    if($admin_id > 0){
        return $data[$admin_id];
    }
}
function admin_log($model,$scene_id,$record_id,$admin_id,$info=null,$before_data=array(),$after_data=array()){
    $add_data                = array();
    $add_data['model']       = $model;
    $add_data['scene_id']    = $scene_id;
    $add_data['record_id']   = $record_id;
    $add_data['admin_id']    = $admin_id;
    $add_data['ip']          = get_client_ip();
    $add_data['before_data'] = serialize($before_data);
    $add_data['after_data']  = serialize($after_data);
    $add_data['create_time'] = time();

    if(C('PK_PLUGIN_NAME')){
        $file = APP_PATH.'Plugins/'.C('PK_PLUGIN_NAME').'/ModelSafety/'.$model.'ModelSafety.class.php';
    }else{
        $file = APP_PATH.C('PK_MODULE_NAME').'/ModelSafety/'.$model.'ModelSafety.class.php';
    }
    if(!is_file($file)){
        return 0;
    }
    if(C('PK_PLUGIN_NAME')){
        $tplData   = D("Plugins://".C('PK_MODULE_NAME')."/".$model,'ModelSafety')->replaceTplByData($add_data,$before_data,$after_data);
    }else{
        $tplData   = D(C('PK_MODULE_NAME')."/".$model,'ModelSafety')->replaceTplByData($add_data,$before_data,$after_data);
    }
    
    if($tplData){
        if($info != ""){
            $add_data['info']        = $info; // todo 是否可以删除
        }else{
            $add_data['info']        = $tplData;
        }
        $res = M('admin_log')->add($add_data);
        return $res;
    }else{
        return 0;
    }
}
// todo 从数据库读取
function admin_local_adminconfig_group($group=""){
    $data['common'] = '通用';
    $data['admin']  = '系统';
    $data['cms']    = 'CMS';
    $data['user']   = '用户';
    if($group != ""){
        return $data[$group];
    }
    return $data;
}
// 目前主要用于admin_log_config内
function admin_get_plugin_name_by_plugin_id($id){
    $name = M('admin_plugin')->where(array('id'=>$id))->getField('name');
    return $name;
}
// 目前主要用于admin_log_config内
function admin_get_group_name_by_admin_id($admin_id){
    $group = M('admin')->where(array('id'=>$admin_id))->getField('group');
    return admin_local_admin_group_name($group);
}
function admin_get_auth_data_by_url($current_url){
    if(!F('admin_get_auth_data_by_url_'.$current_url)){
        $menuIdResult = M('admin_menu')->where(array('url'=>$current_url))->select();
        $menuids_arr = array();
        foreach($menuIdResult as $key=>$value){
            array_push($menuids_arr, $value['id']);
            $top_menu_name = $value['controller_name'];
        }
        // 组装数据
        $data['menuids_arr']    = $menuids_arr;
        $data['top_menu_name']  = $top_menu_name;
        F('admin_get_auth_data_by_url_'.$current_url,$data);
    }
    $data = F('admin_get_auth_data_by_url_'.$current_url);
    return $data;
}
function admimn_get_all_menu(){
    if(!F("admimn_get_all_menu")){
        $top_menus = admin_get_top_admin_menu();
        foreach ($top_menus as $top_key => $top_value) {
            $map             = array();
            $map['pid']      = (int)$top_value['id'];
            $map['display']  = 1;
            $map['status']   = 1;
            $menus = M("admin_menu")->where($map)->order('ordid')->select();
            foreach ($menus as $key => $value) {
                $map['pid']                      = $value['id'];
                $new_menus[$top_key.$key]        = $value;
                $new_menus[$top_key.$key]['top_tab_name'] = $top_value['controller_name'];
                $new_menus[$top_key.$key]['sub'] = M("admin_menu")->where($map)->order('ordid')->select();
            }
        }
        F("admimn_get_all_menu",$new_menus);
    }
    $menus = F("admimn_get_all_menu");
    return $menus;
}
function admin_get_top_admin_menu(){
    if(!F("admin_get_top_admin_menu")){
        $map['pid']      = 0;
        $map['display']  = 1;
        $map['status']   = 1;
        $menus = M("admin_menu")->where($map)->order('ordid')->select();
        F("admin_get_top_admin_menu",$menus);
    }
    $menus = F("admin_get_top_admin_menu");
    return $menus;
}


