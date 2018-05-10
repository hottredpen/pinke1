<?php

namespace Common\Controller;
use Think\Controller;
/**
 * 基础函数控制器
 * 主要是一些基础继承方法
 */
class CommonBaseController extends Controller {
    protected $action_list;
    protected function _initialize() {
        $this->_make_pk_module_controller_action();
        if(!F("WEB_SETTING")){
            $setting = D("Admin/AdminConfig","Datamanager")->getConfigData();
            F("WEB_SETTING",$setting);
        }
        $setting = F("WEB_SETTING");
        C($setting);
    }

    private function _make_pk_module_controller_action(){
        if(!defined('PK_IS_ADMIN')){
            if(strtolower(MODULE_NAME) == 'admin'){
                define('PK_IS_ADMIN', '1'); // 第一次的定义
            }else{
                define('PK_IS_ADMIN', '0'); // 第一次的定义
            }
        }
        // 多次内部重定向mca
        if(strtolower(MODULE_NAME) == 'admin'){
            C('PK_IS_ADMIN',true);
        }else{
            C('PK_IS_ADMIN',false);
        }
        $this->_pk_controller();
    }

    private function _pk_controller(){
        // 多次内部重定向mca
        if(C('PK_IS_ADMIN')){
            if(strstr(strtolower(CONTROLLER_NAME),'admin')){
                if(strtolower(CONTROLLER_NAME) == 'admin'){
                    C('PK_MODULE_NAME','Admin'); // admin内的admin
                }else{
                    C('PK_MODULE_NAME','Admin'); // admin内的admin插件admin
                    $this->_pk_plugin();
                }
            }else{
                $module_name = common_replace_plugin_name_to_module_name(CONTROLLER_NAME);
                if(strtolower(CONTROLLER_NAME) == strtolower($module_name) ){
                    C('PK_MODULE_NAME',CONTROLLER_NAME); // 模块内的admin
                }else{
                    C('PK_MODULE_NAME',$module_name); // 模块内的模块插件的admin
                    $this->_pk_plugin();
                }
            }
        }else{

        }
    }

    private function _pk_plugin(){
        if(is_plugins_pass(ucwords(CONTROLLER_NAME))){
            C('PK_PLUGIN_NAME',CONTROLLER_NAME);
        }else{
            C('PK_PLUGIN_NAME','');
        }
    }

    protected function redirect($url,$params=array(),$delay=0,$msg='') {
        if(common_is_pjax()){
            $_arr    = explode("/", $url);
            $_action = end($_arr);
            $_controller = str_replace("/".$_action, "", $url); //  待优化
            A($_controller)->$_action($params);
        }else{
            $url    =   U($url,$params);
            redirect($url,$delay,$msg);
        }
    }
    
	protected function ajaxReturn($status=1, $msg='', $data='',$code=200) {
        if($status == 0){
            $code = 400;
        }else if($status == 1){
            $code = 200;
        }else if($status > 1){
            $code = $status;
        }
        parent::ajaxReturn(array(
            // 'status'     => $status,
            'msg'        => $msg,
            'code'       => $code,
            'data'       => $data,
        ));
    }
    public function pk_json_return($res){
        $explod_info          = errorinfo_explode_error_code($res['info'],$res['error']);
        $return               = $res;
        $return['msg']        = $res['msg'] ? $res['msg'] : $explod_info['info'];
        $return['code']       = $explod_info['code'];
        unset($return['info']);
        unset($return['error']);
        return $this->json($return);
    }
    protected function json($data){
        parent::ajaxReturn($data);
    }

    protected function pk_error($info="错误",$urlOrData=""){
        // 如果info中有类似：id错误@156，则对156错误code进行拆分处理
        $error_code = -1; // 未定义code
        if(strstr($info,"@")){
            $code_return = errorinfo_explode_error_code($info);
            if((int)$code_return['code'] > 0){
                $error_code = $code_return['code'];
                $info       = $code_return['info'];
            }
        }
        IS_AJAX && $this->ajaxReturn(0,$info,$urlOrData,$error_code);
        if(is_array($urlOrData) && $urlOrData['backurl'] != ''){
            $this->error($info, $urlOrData['backurl']);
        }else{
            $this->error($info, $urlOrData);
        }        
    }

    protected function pk_success($info="成功",$urlOrData=""){
        IS_AJAX && $this->ajaxReturn(1,$info,$urlOrData,200);
        if(is_array($urlOrData) && $urlOrData['backurl'] != ''){
            $this->success($info, $urlOrData['backurl']);
        }else{
            $this->success($info, $urlOrData);
        }
    }

    protected function _pager($count, $pagesize,$parameter="",$is_pjax=1,$not_need_num=false,$pjax_container="#pjax_container") {
        $pager = new \Common\Util\Page($count, $pagesize,$parameter,$is_pjax,$not_need_num,$pjax_container);
		$show       = $pager->show();// 分页显示输出
		return $show;
    }

    protected function hasCacheData($tagname="somefunction",$parameter_arr=""){
        $parameter_str = implode(',', $parameter_arr);
        $cachename     = $tagname.date("YmdH").md5($parameter_str);
        if(S($cachename)===false){
            return false;
        }else{
            return true;
        }
    }

    protected function getCacheData($tagname="somefunction",$data,$parameter_arr=""){
        $parameter_str = implode(',', $parameter_arr);
        $cachename     = $tagname.date("YmdH").md5($parameter_str);
        if(S($cachename)===false){
            S($cachename,$data,3600);
        }
        return S($cachename);
    }

    /**
     * 所有模块通用的调用layout
     */
    protected function layoutDisplay($templateFile,$layout="Public:base"){
        $this->_layoutDisplay($templateFile,$layout);
    }

    /**
     * admin模块调用的layout
     */
    protected function admindisplay($templateFile,$layout="Public:admin_base"){
        C('ADMIN_DISPLAY',1);
        $this->_layoutDisplay($templateFile,$layout);
    }

    private function _layoutDisplay($templateFile,$layout="Public:base"){
        if(strstr(strtolower($templateFile),'common@builder')){
            C('DEFAULT_THEME','');
        }

        if(strstr(strtolower($templateFile),'plugins://')){
            C('DEFAULT_THEME','');
            $templateFile = str_replace("Plugins://", "Plugins/".C('PK_PLUGIN_NAME')."@", $templateFile);
            if(C('ADMIN_DISPLAY')){
                $layout = $layout;
            }else{
                $layout = "Plugins/".C('PK_PLUGIN_NAME')."@".$layout;
            }
        }

        G('viewStartTime');
        // 视图开始标签
        \Think\Hook::listen('view_begin',$templateFile);
        $is_pjax = common_is_pjax();
        if($is_pjax || IS_AJAX){
            // pjax和ajax的情况，都返回无layout的模板文件内容
            $layoutcontent = $this->fetch($templateFile);
            if(IS_AJAX && C('ADMIN_DISPLAY') && !$is_pjax){
                $this->pk_success('',$layoutcontent);exit(); // 普通的ajax返回
            }
        }else{
            // 其他情况，获取带layout的模板文件
            $content = $this->fetch($templateFile);
            $content = preg_replace("/<title>.+<\/title>/is", "", $content);// 去除模板里多余的title标签

            if( common_is_without_layout() ){
                $pk_layout_class = "pk_layout_without";
            }else{
                $pk_layout_class = "";
            }
            $this->assign("pk_layout_class",$pk_layout_class);
            $this->assign("fetch_html",$content);
            $layoutcontent = $this->fetch($layout);
        }
        // 输出模板内容
        $this->_render($layoutcontent);
        // 视图结束标签
        \Think\Hook::listen('view_end');
    }
    private function _render($content,$charset='',$contentType=''){
        if(empty($charset))  $charset = C('DEFAULT_CHARSET');
        if(empty($contentType)) $contentType = C('TMPL_CONTENT_TYPE');
        // 网页字符编码
        header('Content-Type:'.$contentType.'; charset='.$charset);
        header('Cache-control: '.C('HTTP_CACHE_CONTROL'));  // 页面缓存控制
        // 输出模板文件
        echo $content;
    }

    /**
     * 获取筛选条件(RestFull控制器也有此方法)
     */
    final protected function getMap(){
        $search_field     = I('search_field/s', '','trim');
        $keyword          = I('keyword/s', '','trim');
        $filter           = I('_filter/s', '','trim');
        $filter_content   = I('_filter_content/s', '','trim');
        $filter_time      = I('_filter_time/s', '','trim');
        $filter_time_from = I('_filter_time_from/s', '','trim');
        $filter_time_to   = I('_filter_time_to/s', '','trim');

        $map = array();

        // 搜索框搜索
        if ($search_field != '' && $keyword != '') {
            $map[$search_field] = array('like','%'.$keyword.'%');
        }

        // 时间段搜索
        if ($filter_time != '') {
            if($filter_time_from != '' && $filter_time_to != ''){
                $filter_time_from   = strtotime($filter_time_from." 00:00:00 ");
                $filter_time_to     = strtotime($filter_time_to." 23:59:59 ");
                $map[$filter_time]  = array('between',array($filter_time_from, $filter_time_to));
            }
            if($filter_time_from != '' && $filter_time_to == ''){
                $filter_time_from   = strtotime($filter_time_from." 00:00:00 ");
                $map[$filter_time]  = array('gt',$filter_time_from);
            }
            if($filter_time_to != '' && $filter_time_from == ''){
                $filter_time_to   = strtotime($filter_time_to." 23:59:59 ");
                $map[$filter_time]  = array('lt',$filter_time_to);
            }
        }

        // 筛选
        if ($filter != '') {
            $filter         = explode('|', $filter);
            $filter_content = explode('|', $filter_content); // 此处存在为0的情况，如微信未分组的用户筛选。所以下面的不要进行array_filter

            foreach ($filter as $key => $item) {
                $filter_content[$key] = str_replace("-", ",", $filter_content[$key]); // 因http_build_query会将','转义所以部分用了'-',现统一用','

                if(strstr($item,"_ids")){
                    // 如果带有“_ids”字段的进行like+or处理
                    $filter_content_arr[$key]  = explode(",", $filter_content[$key]);
                    $_dd_arr = array();
                    if($filter_content_arr[$key]){
                        foreach ($filter_content_arr[$key] as $key2 => $value2) {
                            array_push($_dd_arr, '%,'.$value2.',%');
                        }
                    }else{
                        $_dd_arr = array("%,0,%");
                    }
                    $map[$item]  = array('like',$_dd_arr,'OR');
                }else{
                    $map[$item]  = array('in',$filter_content[$key]);
                }
            }
        }

        //dump($filter);

        $this->assign('search_field',$search_field);
        $this->assign('keyword',$keyword);
        $this->assign('_filter',implode("|", $filter));
        $this->assign('_filter_content',str_replace(",", "-", implode("|", $filter_content)));
        $this->assign('_filter_time',$filter_time);
        $this->assign('_filter_time_from',date('Y-m-d',$filter_time_from));
        $this->assign('_filter_time_to',date('Y-m-d',$filter_time_to));
        return $map;
    }

    /**
     * 获取字段排序(RestFull控制器也有此方法)
     * @param string $extra_order 额外的排序字段
     * @param bool $before 额外排序字段是否前置
     * @return string
     */
    final protected function getOrder($extra_order = '', $before = false){
        $order = I('_order/s', 'id' ,'trim');
        $by    = I('_by/s', 'desc' ,'trim');
        if ($order == '' || $by == '') {
            return $extra_order;
        }
        if ($extra_order == '') {
            return $order. ' '. $by;
        }
        if ($before) {
            return $extra_order. ',' .$order. ' '. $by;
        } else {
            return $order. ' '. $by . ',' . $extra_order;
        }
    }
}