<?php
/**
 * 中英文字符串转数组
 */
function common_utils_str_split_unicode($str, $l = 0) {
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

/**
 * 移除xss
 */
function common_utils_remove_xss($str){
    static $_HTMLPurifier_obj = null;
    if ($_HTMLPurifier_obj === null) {
        // 载入核心文件
        $file = APP_PATH.'Common/Util/HTMLPurifier/HTMLPurifier.includes.php';
        if(is_file($file)){
            require_once($file);
            $_HTMLPurifier_obj = new \HTMLPurifier();
        }
    }
    // 返回过滤后的数据
    return $_HTMLPurifier_obj->purify($str);
}
// 计算中文字符串长度
function common_utils_utf8_strlen($string = null) {
    // 将字符串分解为单元
    preg_match_all("/./us", $string, $match);
    // 返回单元个数
    return count($match[0]);
}
// A-Z AA-ZZ
function common_utils_excel_colume($i=0){
  $y = ($i / 26);
  if ($y >= 1) {
      $y        = intval($y);
      $chr = chr($y+64).chr($i-$y*26 + 65);
  } else {
      $chr = chr($i+65);
  }
  return $chr;
}
/**
 * 获取远程图片到本地临时文件中
 */
function common_utiles_curl_file_get_contents($durl){
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $durl);
   curl_setopt($ch, CURLOPT_TIMEOUT, 2);
   curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
   curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $r = curl_exec($ch);
   curl_close($ch);
   return $r;
 }