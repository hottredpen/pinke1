<?php
/**
 * 过滤
 * 注意：函数内的filter_name 就是全局通用的，非全局通用的,或者不常用的请直接写在model内，并用callback调用
 * @return 过滤后的值 
 * （收录标准是各系统可用）
 * @命名规范
 * 【全局通用】
 * common_filter_[tagname]          例如 common_filter_textarea
 * common_filter_[name]             例如 common_filter_keywords,common_filter_ids
 * 【自定义】（因各种需求产生的自定义过滤）
 * filter_custom_[yourname]  例如 common_filter_editor_content，filter_custom_input_001
 * 其他非此命名的的函数是内部辅助函数
 */
function common_filter_textarea($str){
    $str = common_utils_remove_xss($str);
    //@todo
    return $str;
}

function common_filter_keywords($str){
    //@todo
    return $str;
}
function common_filter_ids($ids){
    $arr = explode(",", $ids);
    foreach ($arr as $key => $value) {
        $arr[$key] = (int)$value;
    }
    $ids = implode(",", array_filter($arr));
    return $ids;
}
// 多个单词(,)
// todo 用正则此优化方法
function common_filter_words($words){
    $arr = explode(",", $words);
    foreach ($arr as $key => $value) {
        $arr[$key] = common_filter_strs($value);
    }
    $words = implode(",", array_filter($arr));
    return $words;
}
// 单个单词
function common_filter_one_word($word){
    return common_filter_strs($word,50);
}
function common_filter_strs($str,$strlenMax=99){
    if(strlen($str)>$strlenMax){ return "";}
    $str     = str_replace(" ","",$str);
    $pattern = "/^[A-Za-z0-9_]+$/u";
    $strArr = common_utils_str_split_unicode($str);
    $newstr = "";
    foreach ($strArr as $key => $value) {
        if (!preg_match($pattern, $value)){
            $newstr .= "";
        }else{
            $newstr .= $value;
        }
    }
    return $newstr;
}
function common_filter_editor_content($str){
    $str = htmlspecialchars($str);
    return $str;
}