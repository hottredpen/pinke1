<?php
/**
 * 后台的登录，及首页
 *
 * todo目前含有待转移的方法（不知道放何处合适）
 * 
 */
namespace Admin\Controller;

class IndexController extends BackController {
    protected function _initialize() {
    	parent::_initialize();
    }
    /**
     * todo 删除中间内容部分，改用插件调用
     */
    public function index(){
        $message = array();
        if (APP_DEBUG == true) {
            $message[] = array(
                'type' => 'Error',
                'content' => "您网站的 DEBUG 没有关闭，出于安全考虑，我们建议您关闭程序 DEBUG。",
            );
        }
        if (!function_exists("curl_getinfo")) {
            $message[] = array(
                'type' => 'Error',
                'content' => "系统不支持 CURL ,将无法采集数据。",
            );
        }
        $this->assign('message', $message);
        $mysql_version_data = M()->query('select version() as version');
        $mysql_version      = $mysql_version_data[0]['version'];
        $system_info = array(
            'server_domain'       => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            'server_os'           => PHP_OS,
            'web_server'          => $_SERVER["SERVER_SOFTWARE"],
            'php_version'         => PHP_VERSION,
            'mysql_version'       => $mysql_version,
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time'  => ini_get('max_execution_time') . '秒',
            'safe_mode'           => (boolean) ini_get('safe_mode') ?  'onCorrect' : 'onError',
            'zlib'                => function_exists('gzclose') ?  'onCorrect' : 'onError',
            'curl'                => function_exists("curl_getinfo") ? 'onCorrect' : 'onError',
            'timezone'            => function_exists("date_default_timezone_get") ? date_default_timezone_get() : L('no')
        );
        $this->assign('system_info', $system_info);
        $this->assign('time',date('Y-m-d H:i'));
        $this->assign('ip',get_client_ip());
        $this->assign('my_admin', session('admin'));
        $this->theme('one')->admindisplay('panel');
    }

    /**
     * 初次结合oneui的试验
     */
    // public function _empty($name){
    //     $this->theme('one')->layoutDisplay($name);
    // }

    public function login(){
    	if(IS_POST){
            $AdminBaseHandleObject = $this->visitor->AdminBaseHandleObject();
            $res = $AdminBaseHandleObject->login();
    		if($res['error']==0 && $res['admin_id'] >0){
    			$this->pk_success($res['info'], U('index/index'));
    		}else{
    			$this->pk_error($res['info'], U('index/login'));
    		}
    	}else{
            if($this->visitor->info['id'] > 0){
                $this->redirect(U('index/index'));
            }else{
                $this->display("common/login");
            }
    	}
    }

    public function logout(){
        $AdminBaseHandleObject = $this->visitor->AdminBaseHandleObject();
        $res = $AdminBaseHandleObject->logout();
        $this->success($res['info'], U('index/login'));
    }

    public function verify_code() {
        $Verify = new \Think\Verify();
        $Verify->fontSize = 30;
        $Verify->length   = 4;
        $Verify->entry();
    }


    public function getfilterlist(){
        $data         = I("data",'','trim');
        $method       = I("method",'','trim');
        $filter       = I("filter",'','trim');
        $filter_vals  = I("filter_vals",array());
        $backurl      = I("backurl",'','trim');
        $map          = I("map","","trim");
        $search_field = I('search_field','','trim');
        $keyword      = I('keyword','','trim');

        switch ($method) {
            case 'function':
                $name = $data;
                if(strstr($name,'_local_') ){
                    if( strstr($name,'_plugins_local_') ) {
                        $aa_arr          = explode("_plugins_local_", $name);
                        $plugin_name     = ucfirst(common_replace_under_to_ucfirst($aa_arr[0]));
                        $plugin_name_arr = explode("_", $aa_arr[0]);
                        $module_name     = ucfirst($plugin_name_arr[0]);
                        include_once APP_PATH. $module_name .'/Common/function.php';
                        include_once APP_PATH."Plugins/". $plugin_name .'/Common/function.php';
                    }else{
                        $_name_arr   = explode("_", $name);
                        $module_name = ucfirst($_name_arr[0]);
                        include_once APP_PATH. $module_name .'/Common/function.php';
                    }
                    if(function_exists($name)){
                        $data_list   = $name();
                    }else{
                        $this->pk_error('参数错误,目前只支持带‘_local_’函数');
                    }
                }else{
                    if(in_array($name, array("common_sex_name"))){
                        $data_list   = $name();
                    }else{
                        $this->pk_error('参数错误,目前只支持带‘_local_’函数');
                    }
                }
                break;
            case 'options':
                $data_list   = json_decode($data);
                break;
            default:
                # code...
                break;
        }
        $map                     = json_decode($map,true);

        $info['filter']          = $filter; // 当前的筛选字段
        $info['filter_vals']     = str_replace("-", ",", $filter_vals); // 当前字段的值
        $info['_filter']         = implode("|", $map['filter']);
        $info['_filter_content'] = implode("|", $map['filter_content']);
        $info['search_field']    = $search_field;
        $info['keyword']         = $keyword;
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('筛选')
                ->setPostUrl(U('admin/index/go_new_page'))
                ->setBackUrl(U($backurl))
                ->addFormItem('filter_vals', 'filter_box','','',array('data_list'=>$data_list))
                ->addFormItem('filter', 'hidden')
                ->addFormItem('_filter', 'hidden')
                ->addFormItem('_filter_content', 'hidden')
                ->addFormItem('search_field', 'hidden')
                ->addFormItem('keyword', 'hidden')
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    /**
     * 虽然用post方法去获取get页面，看似多了一步，但里面的好处有多个
     * 1、submit生成的url（get方法），但目前不利于pjax
     * 2、需要backurl来过渡，也就是还是需要2步完成，那post和get也就无所谓了
     */
    public function go_new_page(){
        if(!IS_POST){
            return;
        }
        $filter          = I('filter','','trim'); 
        $filter_vals     = I('filter_vals');
        $_filter         = I('_filter','','trim');
        $_filter_content = I('_filter_content','','trim');
        $backurl         = I('backurl','','trim');

        $search_field    = I('search_field','','trim');
        $keyword         = I('keyword','','trim');

        $data['search_field'] = $search_field;
        $data['keyword']      = $keyword;

        if($filter_vals != ""){
            // 这里可以考虑字母排序后排列
            $data['_filter']         = trim($_filter."|".$filter,"|");
            $data['_filter_content'] = trim($_filter_content."|".implode("-", $filter_vals),"|");
        }else{
            // 这里可以考虑字母排序后排列
            $data['_filter']         = trim($_filter,"|");
            $data['_filter_content'] = trim($_filter_content,"|");
            if($data['_filter'] == "" && $data['search_field'] == ""){
                $data = array();
            }
        }

        $param = http_build_query($data,"","&");

        $this->pk_success('刷新页面中',array('bc'=>$backurl,'click_a_to_url'=>U($backurl)."/?".$param));
    }

    /**
     * 检查版本更新
     */
    public function checkversion(){
        //参数设置
        $params = array(
            //系统信息
            'product_name'    => C('pinke.product_name'),
            'product_version' => C('pinke.product_version'),
            'build_version'   => C('pinke.build_version'),

            //用户信息
            // 'data_auth_key'   => sha1(C('AUTHCODE')),
            'website_domain'  => $_SERVER['HTTP_HOST'],
            'server_software' => php_uname() . '_' . $_SERVER['SERVER_SOFTWARE'],
            'website_title'   => C('common_website_name'),
            'auth_username'   => C('common_auth_username'),
            'secret_key'      => C('common_secret_key'),
        );
        $vars = http_build_query($params);

        //获取版本数据
        $conf_arr = array(
            'post' => $params,
        );
        $this->pk_error('连接服务器失败');
        
        $result = json_decode(\Org\Net\Http::fsockopenDownload(C('pinke.product_checkversion'), $conf_arr), true);

        if ($result['status'] == 1) {
            $this->ajaxReturn(1,$result['msg'],$result['data']);
        } else {
            $this->pk_error('连接服务器失败');
        }
    }
    // 选择icon
    // 临时放在该控制器下todo目前没时间做出插件
    public function iconListForDialogChooseOne(){
        $p          = I('p',1,'intval');
        $icon_group = I('icon_group','fa','trim');
        $page_size = 7;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $map['icon_group'] = $icon_group;
        $data_list         = D('Admin/AdminIcon','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num          = D('Admin/AdminIcon','Datamanager')->getNum($map);

        $tab_index = array('fa'=>0,'gl'=>1,'si'=>2);

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('icon列表')
                ->setSearch(array('icon_value'=>'icon的值'),'',U('admin/index/iconListForDialogChooseOne'))
                ->SetTabNav(array(
                        array('title'=>'Font Awesome','href'=>U('Admin/index/iconListForDialogChooseOne',array('icon_group'=>'fa','_without_layout'=>common_is_without_layout()))),
                        array('title'=>'Glyphicons','href'=>U('Admin/index/iconListForDialogChooseOne',array('icon_group'=>'gl','_without_layout'=>common_is_without_layout()))),
                        array('title'=>'SIMPLE LINE','href'=>U('Admin/index/iconListForDialogChooseOne',array('icon_group'=>'si','_without_layout'=>common_is_without_layout())))
                ),$tab_index[$icon_group])
                ->addTableColumn('show_icon', '标识')
                ->setTableDataList($data_list)
                ->setRowClass('J_component_choose_this_company')
                ->setRowData(array('id'=>'__id__','name'=>'__name__'))
                ->setPage($data_num,$page_size)
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilderForIcon');
    }
}