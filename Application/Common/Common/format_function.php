<?php
function common_format_keep_point_two($number){
    return (float)sprintf("%.2f",$number);
}
/**
 * 时间格式化
 */
function common_format_time($time = NULL, $format='Y-m-d H:i') {
    if(strstr($time,'-') || strstr($time,'/')){
        $time = strtotime($time);
    }
    $time = $time === NULL ? time() : intval($time);
    if($time == 0){
        return "";
    }
    return date($format, $time);
}
// common_format_cover_id
function common_format_cover_id($cover_id){
    if($cover_id > 0){
        $url = M('file')->where(array('id'=>$cover_id))->getField('url');
        return $url;
    }else{
        return null;
    }
}
/**
 * cover_ids格式化
 */
function common_format_cover_ids($cover_ids){
    $cover_ids_arr = array_filter(explode(",", $cover_ids));
    if(count($cover_ids_arr) > 0){
        $picfile_data = M("file")->where(array("id"=>array("in",$cover_ids_arr)))->select();
        // 两个foreach是为了排序
        foreach ($cover_ids_arr as $key => $value) {
            foreach ($picfile_data as $key2 => $value2) {
                if($value2['id'] == $value){
                    $pic_data[$key] = $value2;
                }
            }
        }
    }else{
        $pic_data = array();
    }
    return $pic_data;
}
function common_format_file_size($size){
    $kb = 1024;         // Kilobyte
    $mb = 1024 * $kb;   // Megabyte
    $gb = 1024 * $mb;   // Gigabyte
    $tb = 1024 * $gb;   // Terabyte

    if($size < $kb){
        return $size." B";
    }else if($size < $mb){
        return round($size/$kb,2)." KB";
    }else if($size < $gb){
        return round($size/$mb,2)." MB";
    }else if($size < $tb){
        return round($size/$gb,2)." GB";
    }else{
        return round($size/$tb,2)." TB";
    }
}
function common_format_all_service_error_info($res_array=array()){
    $error_info_array = array();
    foreach ($res_array as $key => $value) {
        if($value['error'] > 0){
            $info = errorinfo_explode_error_code($value['info']);
            array_push($error_info_array, $info['info']);
        }
    }
    return implode("-", $error_info_array);
}


