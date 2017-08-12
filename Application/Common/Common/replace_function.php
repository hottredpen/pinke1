<?php
/**
 * 替换敏感词汇
 */
function common_replace_badwords($content) {
    if(!F("common_replace_badwords")){
		$data = M("common_badword")->getField('badword,replaceword');
        F("common_replace_badwords",$data);
    }
    $replaceData = F("common_replace_badwords");
    foreach ($replaceData as $badword => $replaceword) {
        $content = str_replace($badword,$replaceword,$content);
    }
    return $content;
}

function common_replace_dbpre_name_for_leftjoin_map($map,$replace_arr){
    foreach ($map as $key => $value) {
        $field_arr = explode("|", $key);
        foreach ($field_arr as $key2 => $value2) {
        	foreach ($replace_arr as $key3 => $value3) {
	            if($value2 == $key3){
	                $field_arr[$key2] = str_replace($key3, $value3, $value2);
	            }
        	}
        }
        $newmap[implode("|", $field_arr)] = $value;
    }
    return $newmap;
}