<?php

namespace Common\Builder;
use Common\Controller\CommonBaseController;
/**
 * 数据列表生成器
 */
class ListBuilder extends CommonBaseController {

    private $_builder_id             =  "";     // builder_id
    private $_meta_title;                       // 页面标题,只起提示作用
    private $_top_button_list        = array(); // 顶部工具栏按钮组
    private $_search                 = array(); // 搜索参数配置
    private $_tab_nav                = array(); // 页面Tab导航
    private $_table_column_list      = array(); // 表格标题字段
    private $_table_data_list        = array(); // 表格数据列表
    private $_table_data_row_class   = "";
    private $_table_data_row_data    = array();
    private $_table_data_list_key    = 'id';    // 表格数据列表主键字段名
    private $_table_data_page;                  // 表格数据分页
    private $_right_button_list      = array(); // 表格右侧操作按钮组
    private $_alter_data_list        = array(); // 表格数据列表重新修改的项目
    private $_ajax_url               = "#";     // ajax快速编辑的链接
    
    private $_order_columns          = array(); // 需要排序的列表头
    
    private $_filter_columns         = array(); // 需要过滤功能的列表头
    private $_filter_map             = array();     
    private $_filter_display         = array(); // 字段筛选的默认选项(已经选择中的)
    private $_filter_content         = array(); // 字段筛选的默认选中值
    private $_filter                 = array(); // 字段筛选的默认字段名
    private $_filter_time_field_name = "";
    
    private $_checkbox_type          = "checkbox";
    private $_radiobox_trigger       = array();
    private $_is_without_layout      = false;
    public  $origin_method           = "";
    public  $origin_method_hook_arr  = array();
    /**
     * 初始化方法
     * @return $this
     */
    protected function _initialize() {
        $this->_builder_id = common_builder_id_name();
        $this->_is_without_layout = common_is_without_layout();
    }
    /**
     * 获取builder_id
     * 为pjax局部刷新使用
     */
    public function getBuilderId(){
        return $this->_builder_id;
    }

    public function setHooksMethod($origin_method="think\\test::test",$origin_method_hook_arr=array()){
        $this->origin_method          = $origin_method;
        $this->origin_method_hook_arr = $origin_method_hook_arr;
        return $this;
    }

    public function getOriginMethod(){
        return $this->origin_method;
    }

    /**
     * 设置标题，只起提示作用
     */
    public function setMetaTitle($meta_title) {
        if(IS_AJAX && !common_is_pjax()){
            $this->_meta_title = "";
        }else{
            $this->_meta_title = $meta_title;
        }
        return $this;
    }
    /**
     * 设置列表前的checkbox的类型，radio时主要用于弹出窗的单个选择
     * $type 默认为checkbox 也可以设置成radio
     */
    public function setCheckBoxType($type="checkbox"){
        $this->_checkbox_type = $type;
        return $this;
    }
    /**
     * 配合setCheckBoxType使用，当点击时触发的js事件
     * $class_trigger_name  js触发事件
     * $attribute           该js触发事件可能用到的属性
     */
    public function setRadioBoxTrigger($class_trigger_name,$attribute){
        $data['class_name'] = $class_trigger_name;
        $data['attr']       = $attribute;
        $this->_radiobox_trigger = $data;
        return $this;
    }
    /**
     * 设置分页
     * $count 总数
     * $pagesize 页面显示个数
     * $not_need_num 是否只显示上一页下一页
     */
    public function setPage($count,$pagesize,$not_need_num=0){
        $pager      = new  \Common\Util\Page($count, $pagesize,"",1,$not_need_num,"#".$this->getBuilderId());
        $show       = $pager->show();
        $this->_table_data_page = $show;
        return $this;
    }
    /**
     * 快速编辑提交地址
     */
    public function ajax_url($url){
        $this->_ajax_url = $url;
        return $this;
    }
    /**
     * 给某字段添加order排序功能
     */
    public function addOrder($column){
        if (!empty($column)) {
            $column = is_array($column) ? $column : explode(',', $column);
            $this->_order_columns = array_merge($this->_order_columns, $column);
        }
        return $this;
    }
    /**
     * 给某字段添加筛选功能（该方法需要一定的函数支持）
     * $column  字段
     * $method  目前提供 options 和 function 两张方法
     * 当$method为function时，$data 类型为字符，内容为_local_类的函数方法（具体查看函数方法名的定义）
     * 当$method为options时，$data 为数组
     */
    public function addFilter($column,$method,$data){

        $filter_content = I('_filter_content','','trim');
        $filter         = I('_filter','','trim');

        $filter_content_arr = explode("|", $filter_content);
        $filter_arr         = explode("|", $filter);

        foreach ($filter_arr as $key => $value) {
            if($value == $column){
                $ss['filter_vals'] = $filter_content_arr[$key];
                // 将当前过滤的去掉，其他的作为map
                unset($filter_content_arr[$key]);
                unset($filter_arr[$key]);
            }
        }
        $map['filter']         = $filter_arr;
        $map['filter_content'] = $filter_content_arr;
        $ss['map']         = json_encode($map);

        $ss['filter']      = $column;
        $ss['method']      = $method;
        switch ($method) {
            case 'function':
                $ss['data']        = $data;
                break;
            case 'options':
                $ss['data']        = json_encode($data);
                break;
            default:
                # code...
                break;
        }
        $ss['backurl']                  = MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME;//"Admin/cms/document";
        $this->_filter_columns[$column] = $ss;
        return $this;
    }

    /**
     * 加入一个列表顶部工具栏按钮
     * 目前主要为三个batchdelete，layer ，custom
     */
    public function addTopButton($type, $attribute = null) {
        switch ($type) {
            // 批量删除
            case 'batchdelete':

                $my_attribute['title']            = '删除';
                $my_attribute['data-target-from'] = 'ids';
                $my_attribute['class']            = 'btn btn-danger J_confirmurl';
                $my_attribute['data-msg']         = "确定要删除吗？";
                $my_attribute['data-uri']         = "";
                $my_attribute['href']             = "javascript:;";

                if ($attribute && is_array($attribute)) {
                    $my_attribute = array_merge($my_attribute, $attribute);
                }

                $this->_top_button_list[] = $my_attribute;
                break;
            // 自定义的弹窗
            case 'layer':  // 添加新增按钮
                // 预定义按钮属性以简化使用
                $my_attribute['title'] = '新增';
                $my_attribute['class'] = 'J_layer_dialog btn btn-primary';
                $my_attribute['href']  = 'javascript:;';
                // 无参数时直接使用data-action ，有参数时请直接使用data-url
                $my_attribute['data-url'] = U( MODULE_NAME.'/'.CONTROLLER_NAME.'/'.$attribute['data-action']);
                // 替换
                if ($attribute && is_array($attribute)) {
                    $my_attribute = array_merge($my_attribute, $attribute);
                }

                $this->_top_button_list[] = $my_attribute;
                break;
            // 自定义按钮
            case 'custom': 
                $my_attribute['title']       = '未定义title';
                $my_attribute['target-form'] = 'ids';
                $my_attribute['class']       = 'btn btn-primary';

                if ($attribute && is_array($attribute)) {
                    $my_attribute = array_merge($my_attribute, $attribute);
                }

                $this->_top_button_list[] = $my_attribute;
                break;
        }
        return $this;
    }

    /**
     * 设置搜索,如果是连表查询，且两个表有相同字段时，请参考Datamanager类中replaceMap方法
     * $fields 搜索的字段
     * $placeholder 提示语（可不填）
     * $url 搜索的url
     */
    public function setSearch($fields,$placeholder,$url){
        if (!empty($fields)) {
            $this->_search = array(
                'fields'      => $fields,
                'field_all'   => empty($fields) ? '' : implode('|', array_keys($fields)),
                'placeholder' => $placeholder != '' ? $placeholder : '请输入'. implode('/', $fields),
                'url'         => $url
            );
        }
        return $this;
    }

    /**
     * 设置Tab按钮列表
     * $tab_list Tab列表array('title'=>'sss','href'=>'#')  
     * $current_tab_index 当前tab的index
     */
    public function setTabNav($tab_list="", $current_tab_index=0) {
        $this->_tab_nav = array(
            'tab_list' => $tab_list,
            'current_tab' => $current_tab_index
        );
        return $this;
    }
    /**
     * 添加list的列
     */
    public function addTableColumn($name, $title, $type = null, $param = null,$with_field=null) {
        $column = array(
            'name'       => $name,
            'title'      => $title,
            'type'       => $type,
            'param'      => $param,
            'with_field' => $with_field,
        );
        $this->_table_column_list[] = $column;
        return $this;
    }
    /**
     * 设置数据
     */
    public function setTableDataList($table_data_list) {
        $this->_table_data_list = $table_data_list;
        return $this;
    }
    /**
     * 设置每一行里的class
     */
    public function setRowClass($row_class=""){
        $this->_table_data_row_class = $row_class;
        return $this;
    }
    /**
     * 设置每一行里需要被data-name形式赋值的内容
     * $row_data = array('id'=>'__id__','name'=>'__name__')
     * 转换后，在html以以下形式出现
     * data-id = "15"  data-name = "test"
     * @param array $row_data [description]
     */
    public function setRowData($row_data=array()){
        $this->_table_data_row_data = $row_data;
        return $this;
    }

    /**
     * 设置list中checkbox所
     * 全选时ids所对应的值，默认为listdata的id
     */
    public function setListCheckboxFieldName($table_data_list_key="id") {
        $this->_table_data_list_key = $table_data_list_key;
        return $this;
    }
    /**
     * 时间段过滤
     */
    public function addTimeFilter($field = ''){
        $this->_filter_time_field_name = $field;
        return $this;
    }
    /**
     * 加入一个数据列表右侧按钮
     * 需要加入表格参数时采用 __id__,__name__类似格式来获取list数据中当前的id,name等数据
     */
    public function addRightButton($type, $attribute = null) {
        switch ($type) {
            // 弹窗
            case 'layer':
                // 预定义按钮属性以简化使用
                $my_attribute['name']  = 'layer';
                $my_attribute['title'] = '编辑';
                $my_attribute['class'] = 'J_layer_dialog label label-primary';
                $my_attribute['href']  = 'javascript:;';
                // 只有id参数时用data-action ,有其他参数时用data-url
                $argument_array[$this->_table_data_list_key] = '__id__';
                $my_attribute['data-url'] = U( MODULE_NAME.'/'.CONTROLLER_NAME.'/'.$attribute['data-action'],$argument_array);

                // 替换默认值
                if ($attribute && is_array($attribute)) {
                    $my_attribute = array_merge($my_attribute, $attribute);
                }

                $this->_right_button_list[] = $my_attribute;
                break;
            // 带有提示窗口的删除
            case 'delete_confirm':
                // 预定义按钮属性以简化使用
                $my_attribute['name']  = 'delete';
                $my_attribute['title']         = '删除';
                $my_attribute['class']         = 'J_confirmurl label label-danger';
                $my_attribute['href']          = 'javascript:;';
                $my_attribute['data-itemname'] = '列表';
                
                $my_attribute['data-uri'] = U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.$attribute['data-action']);
                $my_attribute['data-msg'] = '确定要删除id=__id__的'.$my_attribute['data-itemname'].'项吗？';
                $my_attribute['data-id']  = '__id__';

                if ($attribute && is_array($attribute)) {
                    $my_attribute = array_merge($my_attribute, $attribute);
                }

                $this->_right_button_list[] = $my_attribute;
                break;
            // 自定义
            case 'custom':
                // 预定义按钮属性以简化使用
                // $my_attribute['target'] = '_self'; // 目前不想pjax
                $my_attribute['title']  = '未定义title';
                $my_attribute['name']   = 'custom';
                $my_attribute['class']  = 'label label-primary';

                // 如果定义了属性数组则与默认的进行合并
                if ($attribute && is_array($attribute)) {
                    $my_attribute = array_merge($my_attribute, $attribute);
                }

                // 这个按钮定义好了把它丢进按钮池里
                $this->_right_button_list[] = $my_attribute;
                break;
        }
        return $this;
    }

    /**
     * 修改列表数据
     * 对列表数据输出前做一次小的修改
     * 比如管理员列表ID为1的超级管理员右侧编辑按钮不显示删除
     * condition  条件     array('key'=>'id','value'=>1)
     * alter_data 修改字段 array('right_button' => $right_button)
     */
    public function alterTableData($condition, $alter_data) {
        $this->_alter_data_list[] = array(
            'condition'  => $condition,
            'alter_data' => $alter_data
        );
        return $this;
    }
    /**
     * 赋值给模板
     */
    public function assign_builder() {
        if($this->origin_method != ''){
            // 例子:
            // Company\Admin\CompanyDealerAdmin::dealer
            // Company_Admin_CompanyDealerAdmin__dealer
            $origin_method = str_replace("\\", "_", $this->origin_method);
            $origin_method = str_replace("::", "__", $origin_method);
            foreach ($this->origin_method_hook_arr as $key => $value) {
                $hook_name = $origin_method.'__'.$value;
                if($value == "addRightButton"){
                    hook($hook_name,$this->_right_button_list);
                }
            }
        }
        // dump($hook_name);
        // exit();
        // $this->origin_method = "test_return_from_hook";
        //  // exit();
        
        // 编译data_list中的值
        foreach ($this->_table_data_list as $data_key => $data) {
            // 为了防止已经被格式的情况，主要用于with_field的功能中,以及_alter_data_list等
            $old_data = $data; 
            // 编译表格右侧按钮
            if ($this->_right_button_list) {
                foreach ($this->_right_button_list as $key => $right_button) {
                    // 将所有参数中待__(.*)__的都进行转化
                    foreach ($right_button as $rr_key => $rr_value) {
                        $right_button[$rr_key]  = $this->_replace_underline_var($right_button[$rr_key],$data);
                    }
                    // 编译按钮属性
                    $right_button['attribute'] = $this->compileHtmlAttr($right_button);
                    $data['right_button'][$right_button['name']][$key] = $right_button;
                }
            }

            // 根据表格标题字段指定类型编译列表数据
            foreach ($this->_table_column_list as &$column) {
                switch ($column['type']) {
                     // 图标
                    case 'icon':
                        $data[$column['name']] = '<i class="fa '.$data[$column['name']].'"></i>';
                        break;
                    // 日期
                    case 'date':
                        $data[$column['name']] = common_format_time($data[$column['name']], 'Y-m-d');
                        break;
                    // 完整日期
                    case 'datetime':
                        $data[$column['name']] = common_format_time($data[$column['name']]);
                        break;
                    // 完整日期
                    case 'time':
                        $data[$column['name']] = common_format_time($data[$column['name']]);
                        break;
                    // 可放大的图片
                    case 'cpk_pic':
                        if($data[$column['name']] !== null){
                            $data[$column['name']] = '<a href="'.$data[$column['name']].'" target="_self" rel="group"><img src="'.common_trans_sub_image($data[$column['name']]).'" height="50"></a>';
                        }else{
                            $data[$column['name']] = '无封面';
                        }
                        break;
                    // 可放大的图片(裁剪版)
                    case 'cpk_pic_sub':
                        if($data[$column['name']] !== null){
                            $data[$column['name']] = '<a href="'.common_trans_sub_image($data[$column['name']]).'" target="_self" rel="group"><img src="'.common_trans_sub_image($data[$column['name']]).'" height="50"></a>';
                        }else{
                            $data[$column['name']] = '无封面';
                        }
                        break;
                    // html
                    case 'html':
                        $data[$column['name']] = str_replace("{{value}}", $data[$column['name']], $column['param']);
                        break;
                    // 开关（状态）
                    case 'switch' :
                        $is_checked = $data[$column['name']] == 1 ? "checked" : "";
                        $data[$column['name']] = '<label class="css-input switch switch-sm switch-primary">
                                                        <input '.$is_checked.' type="checkbox"><span></span>
                                                </label>';
                        break;
                    // 状态（目前也具有开关功能，后期可能为统一使用switch为ajax的操作）
                    case 'status':
                        switch($data[$column['name']]){
                            case '-1':
                                $data[$column['name']] = '<i class="fa fa-trash text-danger"></i>';
                                break;
                            case '0':
                                $tmp_disabled = $data[$column['name']] == 0 ? 'disabled' : 'enabled';
                                $data[$column['name']] = '<img data-tdtype="toggle" data-field="'.$column['name'].'" data-id="'.$data['id'].'" data-value="'.$data[$column['name']].'" src="/static/images/admin/toggle_'.$tmp_disabled.'.gif" />';
                                break;
                            case '1':
                                $tmp_disabled = $data[$column['name']] == 0 ? 'disabled' : 'enabled';
                                $data[$column['name']] = '<img data-tdtype="toggle" data-field="'.$column['name'].'" data-id="'.$data['id'].'" data-value="'.$data[$column['name']].'" src="/static/images/admin/toggle_'.$tmp_disabled.'.gif" />';

                                break;
                        }
                        break;
                    // 快速编辑
                    case 'ajax_edit':
                        $data[$column['name']] = '<span data-tdtype="edit" data-field="'.$column['name'].'" data-id="'.$data[$this->_table_data_list_key].'" class="tdedit">'.$data[$column['name']].'</span>';
                        break;
                    // 简单是数据
                    case 'options':
                        $data[$column['name']] = $column['param'][$data[$column['name']]];
                        break;
                    // 函数方法
                    case 'function': // 调用函数
                        if (is_array($column['param'])) {
                            $_function_param = array();
                            foreach ($column['param'][1] as $_function_key => $_function_value) {
                                  $_function_param[$_function_key] = $this->_replace_underline_var($_function_value,$old_data);
                            }
                            $data[$column['name']] = call_user_func_array($column['param'][0], $_function_param);
                        } else {
                            // 调用函数需要其他字段,with_field方法不如上面的array方法，它可传自定义的字段值（非data内的），后续with_field可能会取消
                            if($column['with_field'] != ""){
                                $with_field_arr = array_filter(explode(",", $column['with_field']));
                                $function_param_arr[$column['name']] = $old_data[$column['name']];
                                foreach ($with_field_arr as  $_field_name) {
                                    $function_param_arr['param'][$_field_name] = $old_data[$_field_name];
                                }
                                $data[$column['name']] = call_user_func_array($column['param'], $function_param_arr);
                            }else{
                                $data[$column['name']] = call_user_func($column['param'], $data[$column['name']]);
                            }
                        }
                        break;
                }
                if (is_array($data[$column['name']]) && $column['name'] !== 'right_button') {
                    $data[$column['name']] = implode(',', $data[$column['name']]);
                }
            }

            /**
             * 修改列表数据
             */
            if ($this->_alter_data_list) {
                foreach ($this->_alter_data_list as $alter) {
                    if ($old_data[$alter['condition']['key']] === $alter['condition']['value']) {
                        if ($alter['alter_data']['right_button']) {
                            foreach ($alter['alter_data']['right_button']['no'] as &$val) {
                                $val['attribute'] = $this->_replace_underline_var($val['attribute'],$data);
                            }
                        }
                        $data = array_merge($data, $alter['alter_data']);
                    }
                }
            }

            // 重新赋值
            $this->_table_data_list[$data_key] = $data;

            // 替换
            foreach ($this->_table_data_row_data as $_table_data_row_data_key => $_table_data_row_data_value) {
                $this->_table_data_list[$data_key]['_table_data_row_data'][$_table_data_row_data_key] = $this->_replace_underline_var($_table_data_row_data_value,$data);
            }
            
        }

        //编译top_button_list中的HTML属性
        if ($this->_top_button_list) {
            foreach ($this->_top_button_list as &$button) {
                $button['attribute'] = $this->compileHtmlAttr($button);
                $button['attribute'] = $this->_replace_underline_var($button['attribute'],$data);
            }
        }

        // 处理字段排序
        if ($this->_order_columns) {
            $order_columns = array();
            foreach ($this->_order_columns as $key => $value) {
                if (is_numeric($key)) {
                    if (strpos($value, '.')) {
                        $tmp = explode('.', $value);
                        $order_columns[$tmp[1]] = $value;
                    } else {
                        $order_columns[$value] = $value;
                    }
                } else {
                    if (strpos($value, '.')) {
                        $order_columns[$key] = $value;
                    } else {
                        $order_columns[$key] = $value. '.' .$key;
                    }
                }
            }
            $this->_order_columns = $order_columns;
        }

        // 统一添加到一个变量里
        $list_builder                        = array();
        $list_builder['builder_id']          = $this->_builder_id;        // 取消padding
        $list_builder['tab_nav']             = $this->_tab_nav;           // 页面Tab导航
        $list_builder['top_button_list']     = $this->_top_button_list;   // 顶部工具栏按钮
        $list_builder['search']              = $this->_search;            // 搜索配置
        $list_builder['table_column_list']   = $this->_table_column_list; // 表格的列
        $list_builder['table_data_list']     = $this->_table_data_list;   // 表格数据
        $list_builder['table_data_list_key'] = $this->_table_data_list_key;   // 表格数据主键字段名称
        $list_builder['table_data_row_class']= $this->_table_data_row_class;
        $list_builder['table_data_row_data'] = $this->_table_data_row_data;
        $list_builder['table_data_page']     = $this->_table_data_page;   // 数据分页
        $list_builder['ajax_url']            = $this->_ajax_url;          // ajax_url
        $list_builder['checkbox_type']       = $this->_checkbox_type;     // input的类型
        $list_builder['radiobox_trigger']    = $this->_radiobox_trigger;  // 为radio时，点击触发的class名（待优化为直接触发，监听触发值就可以）
        $list_builder['order_columns']       = $this->_order_columns;     // 需要排序的字段
        $list_builder['filter_columns']      = $this->_filter_columns;    // 需要筛选的字段
        $list_builder['filter_time']         = $this->_filter_time_field_name; // 时间筛选
        $list_builder['meta_title']          = $this->_meta_title;
        $list_builder['is_without_layout']   = $this->_is_without_layout;
        // dump($this->_table_data_list);
        // exit();

        $this->assign('list_builder',$list_builder);

        return $this;

    }

    //编译HTML属性
    protected function compileHtmlAttr($attr) {
        $result = array();
        foreach ($attr as $key => $value) {
            $value = htmlspecialchars($value);
            $result[] = "$key=\"$value\"";
        }
        $result = implode(' ', $result);
        return $result;
    }

    /**
     * 替换__name__类似的具体数据
     */
    private function _replace_underline_var($value,$data){
        // 升级版，可以用__openid__这种类型获取$data['openid']的值
        // 多个的替换的优化升级
        preg_match_all("/__([\w]*)__/", $value, $all_match,PREG_SET_ORDER);
        for($i=0; $i< count($all_match); $i++){
            $value = str_replace($all_match[$i][0], $data[$all_match[$i][1]], $value);
        }
        return $value;
    }

}