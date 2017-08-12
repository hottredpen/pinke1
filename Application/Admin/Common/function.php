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

function admin_local_admin_group_name($admin_group){
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
    if($info != ""){
        $add_data['info']        = $info;
    }else{
        $add_data['info']        = D('Admin/AdminLogTpl','Datamanager')->replaceTplByData($add_data,$before_data,$after_data);
    }
    $add_data['before_data'] = serialize($before_data);
    $add_data['after_data']  = serialize($after_data);
    $add_data['create_time'] = time();
    $res = M('admin_log')->add($add_data);
    return $res;
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


