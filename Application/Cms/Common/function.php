<?php
// cms_local_category_id_name 
function cms_local_category_id_name($category_id){
	if(!F('cms_local_category_id_name')){
		$data = M('cms_category')->where(array('status'=>1))->getField('id,title');
		F('cms_local_category_id_name',$data);
	}
	$cms_local_category_id_name = F('cms_local_category_id_name');
	if($category_id > 0){
		return $cms_local_category_id_name[$category_id];
	}
	return $cms_local_category_id_name;
}
// cms_trans_enname_to_category_id
function cms_trans_enname_to_category_id($enname){
	if(!F('cms_trans_enname_to_category_id')){
		$data = M('cms_category')->where(array('status'=>1))->getField('name,id');
		F('cms_trans_enname_to_category_id',$data);
	}
	$cms_trans_enname_to_category_id = F('cms_trans_enname_to_category_id');
	if($enname != ""){
		return $cms_trans_enname_to_category_id[$enname];
	}
	return "";
}

function cms_is_current_category($controller_action,$controller,$action){
	if(strtolower($controller_action) == strtolower($controller)."_".strtolower($action)){
		return true;
	}
	return false;
}
// cms_format_list_p_data
function cms_format_list_p_data($list_p_data){
	$dataArray  = array_filter(explode("\n", $list_p_data));

	foreach ($dataArray as $key => $value) {
		if(strstr($value,"=>url=>")){
			list($txt,$url) = explode("=>url=>", $value);
			$dataArray[$key] = "<a href='".$url."' >".$txt."</a>";
		}

		if(strstr($value,"=>img=>")){
			list($txt,$img) = explode("=>img=>", $value);
			$dataArray[$key] = "<a title='".$txt."' ><img width='100' src='".$img."' alt='".$txt."'></a>";
		}

		if(strstr($value,"=>fa=>")){
			list($txt,$faclass) = explode("=>fa=>", $value);
			$dataArray[$key] = "<i class='fa ".$faclass."'></i>&nbsp;&nbsp;".$txt."";
		}
	}
	return $dataArray;
}

function cms_format_otherdata($otherdata){
	$dataArray  = array_filter(explode("\n", $otherdata));
	foreach ($dataArray as $key => $value) {
		list($txt,$url) = array_filter(explode("=>", $value));
		$return[$txt] = $url;
	}
	return $return;
}
// cms_local_product_category_id_title
function cms_local_product_category_id_title($product_category_id){
	if(!F('cms_local_product_category_id_title')){
		$data = M('cms_product_category')->where(array('status'=>1))->getField('id,title');
		F('cms_local_product_category_id_title',$data);
	}
	$cms_local_product_category_id_title = F('cms_local_product_category_id_title');
	if($product_category_id > 0){
		return $cms_local_product_category_id_title[$product_category_id];
	}
	return $cms_local_product_category_id_title;
}
// cms_local_product_category_id_name
function cms_local_product_category_id_name($product_category_id){
	if(!F('cms_local_product_category_id_name')){
		$data = M('cms_product_category')->where(array('status'=>1))->getField('id,name');
		F('cms_local_product_category_id_name',$data);
	}
	$cms_local_product_category_id_name = F('cms_local_product_category_id_name');
	if($product_category_id > 0){
		return $cms_local_product_category_id_name[$product_category_id];
	}
	return $cms_local_product_category_id_name;
}
// cms_local_block_category_id_title
function cms_local_block_category_id_title($block_category_id){
	if(!F('cms_local_block_category_id_title')){
		$data = M('cms_block_category')->where(array('status'=>1))->getField('id,title');
		F('cms_local_block_category_id_title',$data);
	}
	$cms_local_block_category_id_title = F('cms_local_block_category_id_title');
	if($block_category_id > 0){
		return $cms_local_block_category_id_title[$block_category_id];
	}
	return $cms_local_block_category_id_title;
}

