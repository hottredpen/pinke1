<?php
/**
 * 将一种格式转成另一种格式，与format不同的是，trans一般成对出现，且过程可逆
 * 微信中的media_id 与本地资源file_id   看上去是成对出现的，但是不是每个media_id都是对应文件的，也可能是图文的media_id，所以用format较好
 * 复杂一点的内容数据的trans，还是不用函数的好,用Datamanager去管理
 */
// common_trans_aaa_to_bbb

// 
function common_trans_plugin_config_data($origin_data){
    foreach ($origin_data as $key => $value) {
        $item_list[$key]['name']          = $value[0];
        $item_list[$key]['type']          = $value[1];
        $item_list[$key]['title']         = $value[2];
        $item_list[$key]['tip']           = $value[3];
        $item_list[$key]['options']       = $value[4];
        $item_list[$key]['extra']         = $value[5];
        $item_list[$key]['value']         = $value[6];
        // 默认值
        $info[$item_list[$key]['name']]   = $item_list[$key]['default_value'];
    }
    $info['group'] = (int)$group; // todo 
    return array('item_list'=>$item_list,'info'=>$info);
}

/**
 * 获取裁剪图（如果有）
 */
function common_trans_sub_image($url) {
    $path     = substr($url, 0, strripos($url, '/'))."/";
    $filename = ltrim(substr($url,strripos($url, '/')),"/");
    $subpath  = $path."sub_".$filename;
    if(file_exists(trim($subpath,"/"))){
        return "/".trim($subpath,"/");
    }else{
        return common_trans_scale_image($url);
    }
}
// 获取缩略图（如果有）
function common_trans_scale_image($url){
    $path = substr($url, 0, strripos($url, '/'))."/";
    $filename = ltrim(substr($url,strripos($url, '/')),"/");
    $scalepath = $path."scale_".$filename;
    if(file_exists(trim($scalepath,"/"))){
        return "/".trim($scalepath,"/");
    }else{
        return $url;
    }
}
// 获取原图
function common_trans_origin_image($url){
    $url = str_replace("sub_","",$url);
    $url = str_replace("scale_","",$url);
    return $url;
}