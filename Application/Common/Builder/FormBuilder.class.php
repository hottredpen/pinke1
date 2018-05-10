<?php

namespace Common\Builder;
use Think\View;
use Common\Controller\CommonBaseController;
/**
 * 表单生成器
 */
class FormBuilder extends CommonBaseController {

    protected $_component_alias = array();  // 组件的别名数据
    protected $_builder_module = "Common";  // 组件所在模块或者插件 例如 Admin  或者 Plugins://WeixinCard

    private $_builder_id =  "";        // builder_id
    private $_meta_title;              // 页面标题,目前无作用，起提示作用
    private $_form_token;              // 根据生成表单的m,c,a生成的token（加上本地的salt）
    private $_tab_nav;                 // Tab导航
    private $_form_items_tab;          // 同表单内Tab导航（tab内的内容同时都提交）
    private $_post_url;                // 表单提交地址
    private $_form_items  = array();   // 表单项目
    private $_extra_items = array();   // 追加已经构造好的表单项目
    private $_form_data;               // 表单数据
    private $_all_item_class;          // 每一个item都有的class
    private $_not_padding;             // 添加任意值后col-md-12等几个带有padding的class名将被改写，hack实现not-padding  
    private $_item_col_data = array(
            'xs_l'        => "3",
            'xs_r'        => "7",
            'sm_l'        => "3",
            'sm_r'        => "7",
            'md_l'        => "3",
            'md_r'        => "7",
            'lg_l'        => "3",
            'lg_r'        => '7',
            'label_class' => '',
            'input_class' => ''
        );                                   // item 提示文字与内容部分的col-x的值（看情况自行调节）


    private $_from_col_class = "col-md-12";  // 整个表单的宽度
    private $_class_trigger_data;            // 某item为相应值时，其他组件的class添加移除所需的class
    private $_items_values_change_trigger_data;

    /**
     * 初始化方法
     */
    protected function _initialize() {
        $this->_builder_id = common_builder_id_name();
        $this->_make_form_token();
    }

    /**
     * 获取builder_id;
     * 一般为pjax局部刷新使用
     */
    public function getBuilderId($name){
        return $this->_builder_id;
    }
    /**
     * 获取组件的别名数据
     */
    public function getComponentAliasData(){
        return $this->_component_alias;
    }
    /**
     * 设置页面标题
     * 除弹窗（ajax）外，其他都显示
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
     * 加入一个表单项
     * @param $tab   同一表单，item所在TAB（index）
     * @param $group 所在组
     * @param $name  表单名     
     * @param $type  表单类型
     * @param $title 表单标题
     * @param $tip   表单提示说明
     * @param $options 表单options,主要为数据类型，或者必须的数据类型
     * @param $extra   额外自定义项目,主要为组件非必须的，用于展示、触发及关联额外其他item的拓展数据
     * @return $this
     */
    public function addFormItem($name="", $type="", $title="", $tip="", $options = array(), $extra = array()) {
        $item['tab']         = 0;
        $item['group']       = 0;
        $item['name']        = $name;
        $item['type']        = $this->_replace_alias_type($type);
        $item['title']       = $title;
        $item['tip']         = $tip;
        $item['options']     = $options;
        $item['extra']       = $extra;

        if(isset($item['extra']['item_col'])){
            $item['item_col'] = $item['extra']['item_col'];
        }

        $this->_form_items[] = $item;
        // dump($item);
        return $this;
    }
    /**
     * 直接添加items，需在addFormItem之前使用
     */
    public function setItemsData($items_data){
        $this->_form_items = $items_data;
        foreach ($this->_form_items as $key => $value) {
            $this->_form_items[$key]['type'] = $this->_replace_alias_type($value['type']);
        }
        return $this;
    }
    /**
     * 额外添加，追加已有的form_item
     */
    public function setExtraItems($extra_items) {
        $this->_extra_items = $extra_items;
        foreach ($this->_extra_items as $key => $value) {
            $this->_extra_items[$key]['type'] = $this->_replace_alias_type($value['type']);
        }
        return $this;
    }
    /**
     * 设置组，将form_item进行一分组，方便显示隐藏，在视觉上，有一个虚框包围
     * 注意：此项必须放于addFormItem之后
     */
    public function setItemToGroup($item_name,$group_id,$group_title="", $item_col_data = array() ,$group_item_width_col_class="col-md-6"){
        $_arr = explode(",", $item_name);
        foreach ($_arr as $key => $value) {
            foreach ($this->_form_items as $key2 => $value2) {
                if($value2['name'] == $value){
                    $this->_form_items[$key2]['group']       = $group_id;
                    $this->_form_items[$key2]['group_title'] = $group_title;
                    $this->_form_items[$key2]['group_index'] = $key;
                    $this->_form_items[$key2]['group_item_width_col_class']  = $group_item_width_col_class;
                    $this->_form_items[$key2]['item_col'] = $item_col_data;
                }
            }
        }
        return $this;
    }
    // todo setItemToGroup的数据化方法
    public function setItemToGroupData($data){
        foreach ($data as $key => $value) {
            # code...
        }
    }
    /**
     * 设置tab
     */
    public function setTabNav($tab_list=array(), $current_tab=0) {
        $this->_tab_nav = array('tab_list' => $tab_list, 'current_tab' => $current_tab);
        return $this;
    }
    /**
     * 添加item到不同tab,实现同一表单，多个tab切换（主要用于表单项过多，需要分页显示的情况）
     * 注意：此项必须放于addFormItem之后
     */
    public function setItemToTab($item_name,$tab_id,$tab_title=""){
        $_arr = explode(",", $item_name);
        foreach ($_arr as $key => $value) {
            foreach ($this->_form_items as $key2 => $value2) {
                if($value2['name'] == $value){
                    $this->_form_items[$key2]['tab']      = $tab_id;
                    $this->_form_items[$key2]['tab_title']   = $tab_title;
                    $this->_form_items[$key2]['tab_index']   = $key;
                }
            }
        }
        return $this;
    }
    /**
     * 为每一个item 都添加的class
     */
    public function setAllItemClass($class){
        $this->_all_item_class = $class;
        return $this;
    }
    /**
     * 内容项名称和内容的比例
     */
    public function setFormItemCol_xs_sm_md_lg($userconfig){
        $this->_item_col_data = array_merge($this->_item_col_data, $userconfig);
        return $this;
    }
    /**
     * 整个表单的宽度的class
     */
    public function setFormColClass($col_class){
        $this->_from_col_class = $col_class;
        return $this;
    }
    /**
     * 设置为不需要padding
     * $value值可以随意
     * 如果以后需求有增加，该方法需优化，目前解决方法带有临时性
     */
    public function setNotPadding($value){
        $this->_not_padding    = $value;
        return $this;
    }

    /**
     * 设置添加class触发
     * 会不会使用setValueTriggerClass名称会更好一些
        //$trigger_items 内对应 (formitem,addClassName,removeClassName)

        ->setClassTrigger('reply_num','1',array(
            array('component_2,component_3,component_4,component_5','hidden',''),
            array('component_1','','hidden')
        ))
        ->setClassTrigger('reply_num','2',array(
            array('component_3,component_4,component_5','hidden',''),
            array('component_1,component_2','','hidden'),
            array('xiaofeixuanze','','hidden','Jt_each_radio_checked_trigger_event_[this]'),
        ))

        $trigger_items 的第一个参数是需要添加的class 第二个是需要移除的class，第三个是需要再次触发的事件(如果是带有[this]，则需要相应的组件用js去支持 详见radio组件)

     */
    public function setClassTrigger($on_field, $on_values , $trigger_items=array()){
        $data['on_field']                   = $on_field;
        if(is_array($on_values)){
            $data['on_values']              = implode("||", $on_values); // 表示多个值都可触发
        }else{
            $data['on_values']              = $on_values;
        }
        foreach ($trigger_items as $key => $value) {
            $item[$key] = implode("&", $value);
        }
        $data['trigger_items'] = implode("|", $item);
        $this->_class_trigger_data[] = $data;
        return $this;
    }

    /**
     * 绑定同一表单内的值变化
     * 是上一个方法高级实现,
     * 上面的可以成为该方法的一个trigger_type= "change_class" 的实现,后期对外可只保留此方法
     * @return [type] [description]
     */
    public function setItemsValuesChangeTrigger($trigger_type="bind",$trigger_event_name="",$trigger_to=array()){
        $data['trigger_type']       = $trigger_type;
        $data['trigger_event_name'] = $trigger_event_name;
        $data['trigger_to']         = $trigger_to;
        $this->_items_values_change_trigger_data[] = $data;
        return $this;
    }

    /**
     * 设置表单提交地址
     */
    public function setPostUrl($post_url) {
        $this->_post_url = $post_url;
        return $this;
    }
    /**
     * 设置表单提交后的回掉地址
     */
    public function setBackUrl($post_backurl){
        $this->_post_backurl = $post_backurl;
        return $this;
    }
    /**
     * 设置表单表单数据
     */
    public function setFormData($form_data) {
        $this->_form_data = $form_data;
        return $this;
    }
    /**
     * 赋值页面内容到模板
     */
    public function assign_builder() {
        //额外已经构造好的表单项目与单个组装的的表单项目进行合并
        $this->_form_items = array_merge($this->_form_items, $this->_extra_items);

        //编译表单值
        if ($this->_form_data) {
            foreach ($this->_form_items  as $key => $value) {
                if ($this->_form_data[$value['name']]) {
                    $this->_form_items[$key]['value'] = $this->_form_data[$value['name']];
                }
                // 将options里是值（__id__等）替换
                if(isset($value['options'])){
                    foreach ($value['options'] as $key2 => $value2) {
                        if(is_string($value2)){
                            $this->_form_items[$key]['options'][$key2] = $this->_replace_underline_var($value2,$this->_form_data);
                        }
                        if(is_array($value2)){
                            foreach ($value2 as $key3 => $value3) {
                                $this->_form_items[$key]['options'][$key2][$key3] = $this->_replace_underline_var($value3,$this->_form_data);
                            }
                        }
                    }
                }
                // 将extra里是值（__id__等）替换
                if(isset($value['extra'])){
                    foreach ($value['extra'] as $key2 => $value2) {
                        if(is_string($value2)){
                            $this->_form_items[$key]['extra'][$key2] = $this->_replace_underline_var($value2,$this->_form_data);
                        }
                        if(is_array($value2)){
                            foreach ($value2 as $key3 => $value3) {
                                $this->_form_items[$key]['extra'][$key2][$key3] = $this->_replace_underline_var($value3,$this->_form_data);
                            }
                        }
                    }
                }
            }
        }

        $group_data = array();
        // group 预先抽离出组
        foreach ($this->_form_items as $key => $value) {
            // 如果本item已被放入组
            if($value['group'] > 0){
                $group_data[$value['group']][$value['group_index']] = $value;
                if($value['group_index'] == 0){
                    $this->_form_items[$key]['name'] = 'group_'.$value['group'];
                    $this->_form_items[$key]['type'] = 'group';
                    $this->_form_items[$key]['title'] = $value['group_title'];
                    $this->_form_items[$key]['item_col'] = $this->_item_col_data;
                }else{
                    unset($this->_form_items[$key]);
                }
            }else{
                if(!isset($this->_form_items[$key]['item_col'])){
                    $this->_form_items[$key]['item_col'] = $this->_item_col_data;
                }
            }
        }
        
        foreach ($this->_form_items as $key => $value) {
            // group 嫁接
            if($value['group'] > 0){
                $this->_form_items[$key]['group_items'] = $group_data[$value['group']];  
            }
            // form_items_tab
            if($value['tab'] > 0){
                $this->_form_items_tab[$value['tab']][$value['tab_index']] = $value; 
            }
        }
        // 默认将form_items嫁接到index=0下
        if(count($this->_form_items_tab) == 0){
            $this->_form_items_tab[0] = $this->_form_items;
        }
        //exit();

        // 统一添加到一个变量里
        $form_builder                       = array();
        $form_builder['builder_id']         = $this->_builder_id;     // builder_id
        $form_builder['tab_nav']            = $this->_tab_nav;        // tab
        $form_builder['all_item_class']     = $this->_all_item_class; // 所有表单item都有class
        $form_builder['post_url']           = $this->_post_url;       // 表单提交地址
        $form_builder['post_backurl']       = $this->_post_backurl;   // 提交后的返回地址
        $form_builder['form_items_tab']     = $this->_form_items_tab; // 同一表单内的tab
        $form_builder['form_items']         = $this->_form_items;     // 所有item
        $form_builder['from_col_class']     = $this->_from_col_class; // 整个内容区的宽度
        $form_builder['class_trigger_data'] = $this->_class_trigger_data; // 整个内容区的宽度
        $form_builder['not_padding']        = $this->_not_padding;        // 取消padding
        $form_builder['item_col']           = $this->_item_col_data;      // 表格，内容项名称和内容的比例(不同显示屏不同比例)
        $form_builder['form_token']         = $this->_form_token; // 表格的token
        $form_builder['builder_module']     = $this->_builder_module; // 所处模块
        $form_builder['meta_title']         = $this->_meta_title;
        
        $form_builder['items_values_change_trigger_data'] = $this->_items_values_change_trigger_data;
        // dump($form_builder);
        // exit();
        $this->assign('form_builder',$form_builder);

        // 无法给<BeforeTemplate:builder>赋值变量，只能用动态C方法
        C('PK_BUILDER_MODULE',$this->_builder_module);

        return $this;

    }

/**
 ********************************
 *   私有方法
 ********************************
 */
    private function _make_form_token(){
        $token_salt        = common_random_string(20).MODULE_NAME.CONTROLLER_NAME.ACTION_NAME;
        $this->_form_token = $token_salt.'-'.md5(get_client_ip().$token_salt. strtolower(MODULE_NAME.CONTROLLER_NAME.ACTION_NAME) );
    }
    /**
     * 替换为组件对应的具体组件版本或者别名
     */
    private function _replace_alias_type($type){
        $alias_type = $this->_component_alias[$type];
        if(isset($alias_type)){
            return $alias_type;
        }
        return $type;
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
