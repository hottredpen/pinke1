<?php
/**
 * seo 从老系统中移植过来
 * 目前精简度还不够，等有空继续优化
 */
/**
 * @param  array   $seo_replace_init 需要添加的变量数组，array('info_title'=>$article['title'])
 * @param  integer $other 是否是相同action下不同的页面，附加参数为0~100
 */
function seo_init($seo_replace_init=array(),$other=0){
    $seo = getSEOData(CONTROLLER_NAME,ACTION_NAME,$other);
    $page_seo = _config_seo_replace(array(
            'title'       => $seo['title_tpl'],
            'keywords'    => $seo['keywords_tpl'],
            'description' => $seo['description_tpl']
    ),$seo_replace_init);
    C('META_TITLE',$page_seo['title']);
    C('META_KEYWORDS',$page_seo['keywords']);
    C('META_DESCRIPTION',$page_seo['description']);
    return $page_seo;
}
/*
 * SEO替换
 */
function _config_seo_replace($page_seo= array(), $data = array()) {
    //开始替换
    $searchs  = array('{site_name}', '{site_title}', '{site_keywords}', '{site_description}');
    $replaces = array(C('common_website_name'), C('common_website_title'), C('common_website_keyword'), C('common_website_description'));
    preg_match_all("/\{([a-z0-9_-]+?)\}/", implode(' ', array_values($page_seo)), $pageparams);

    if ($pageparams) {
        foreach ($pageparams[1] as $var) {
            $searchs[] = '{' . $var . '}';
            $replaces[] = $data[$var] ? strip_tags($data[$var]) : '';
        }
        //符号
        $searchspace = array('((\s*\-\s*)+)', '((\s*\,\s*)+)', '((\s*\|\s*)+)', '((\s*\t\s*)+)', '((\s*_\s*)+)');
        $replacespace = array('-', ',', '|', ' ', '_');
        foreach ($page_seo as $key => $val) {
            $page_seo[$key] = trim(preg_replace($searchspace, $replacespace, str_replace($searchs, $replaces, $val)), ' ,-|_');
        }
    }
    return $page_seo;
}
function getSEOData($controller,$action,$other){
    if(in_array(strtolower(MODULE_NAME) , array('home','child','car'))){
        $pre_name = "";
    }else{
        $pre_name = strtolower(MODULE_NAME)."_";
    }
    $data = array();
    if(!hasCacheData($pre_name."getSEOData_",func_get_args())){
        $data = M($pre_name."seo")->where(array("module"=>$controller,"action"=>$action,"other"=>$other,'status'=>1,'pid'=>array('gt',0)))->find();
    }
    return getCacheData($pre_name."getSEOData",$data,func_get_args());
}
function hasCacheData($tagname="somefunction",$parameter_arr=""){
    $parameter_str = implode(',', $parameter_arr);
    $cachename     = $tagname.date("YmdH").md5($parameter_str);
    if(S($cachename)===false){
        return false;
    }else{
        return true;
    }
}
function getCacheData($tagname="somefunction",$data,$parameter_arr=""){
    $parameter_str = implode(',', $parameter_arr);
    $cachename     = $tagname.date("YmdH").md5($parameter_str);
    if(S($cachename)===false){
        S($cachename,$data,3600);
    }
    return S($cachename);
}