<?php 
namespace Admin\Admin;

class AdminPluginAdmin extends AdminBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function plugin() {
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Admin/AdminPlugin','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Admin/AdminPlugin','Datamanager')->getNum($map);

        // 未安装时的按钮
        $right_button['no'][0]['title']     = '安装';
        $right_button['no'][0]['attribute'] = 'class="label label-success J_ajax_post_url" href="javascript:;" data-url='.U('admin/admin/install_plugin').' data-name="__name__" data-backurl="'.U('admin/admin/plugin').'" ';


        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('插件列表')
                ->SetTabNav(array(
                        array('title'=>'本地','href'=>'javascript:;'),
                    ),0)
                ->setSearch(array('title'=>'插件名称'),'',U('admin/admin/plugin'))
                ->addOrder('last_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '插件名称')
                ->addTableColumn('version', '版本号')
                ->addTableColumn('author', '作者')
                ->addTableColumn('description', '简介')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('custom',array('href'=>U('admin/__name__/index'),'class'=>'label label-success','title'=>'管理'))
                ->addRightButton('delete_confirm',array('data-uri'=>U('admin/admin/uninstall_plugin',array('name'=>'__name__')),'data-itemname'=>'管理员','title'=>'卸载','data-msg'=>'确定进行此操作?'))
                ->alterTableData(
                    array('key' => 'status', 'value' => '-1'),
                    array('right_button' => $right_button)
                )
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }
    /**
     * 安装插件
     */
    public function install_plugin(){
        $plugin_name = I('name','','trim');

        $plugin_class = common_get_plugin_class($plugin_name);
        if (!class_exists($plugin_class)) {
            $this->pk_error('插件不存在！');
        }
        // 实例化插件
        $plugin = new $plugin_class;
        // 插件预安装
        if(!$plugin->install()) {
            $this->pk_error('插件预安装失败!原因：'. $plugin->getError());
        }
        // 安装插件
        $res_plugin = D('Admin/AdminPlugin','Service')->install($plugin_name,$plugin->info,$plugin->hooks,$plugin->database_prefix);
        if($res_plugin['error'] == 1){
            $this->pk_error($res_plugin['info']);
        }
        $this->pk_success('插件安装成功',array('backurl'=>U('admin/admin/plugin')));
    }

    /**
     * 卸载插件
     */
    public function uninstall_plugin($name = ''){
        $id  = I('id',0,'intval');
        $has = M('admin_plugin')->where(array('id'=>$id))->find();

        $plugin_name = $has['name'];
        $class       = common_get_plugin_class($plugin_name);

        if (!class_exists($class)) {
            return $this->error('插件不存在！');
        }
        // 实例化插件
        $plugin = new $class;
        // 插件预卸载
        if(!$plugin->uninstall()) {
            $this->pk_error('插件预卸载失败!原因：'. $plugin->getError());
        }
        // 卸载插件
        $res_plugin = D('Admin/AdminPlugin','Service')->uninstall($plugin_name,$plugin->database_prefix);
        if($res_plugin['error'] == 1){
            $this->error($res_plugin['info']);
        }
        $this->pk_success('插件卸载成功',array('backurl'=>U('admin/admin/plugin')));

    }

    public function update_plugin_config(){
        $id          = I('id',0,'intval');
        $data        = $_POST;
        $model       = M('admin_plugin');
        $has         = $model->where(array('id'=>$id))->find();
        if($has){
            $res = $model->where(array('id'=>$has['id']))->save(array('config'=>serialize($data)));
            if($res){
                $this->pk_success('更新成功');
            }else{
                $this->pk_error('更新失败');
            }
        }else{
            $this->pk_error('不存在插件');
        }
    }

    // 已废弃
    public function plugin_config(){
        $id = I('id',0,'intval');

        $data      = M('admin_plugin')->where(array('id'=>$id))->find();
        $config    = include APP_PATH . 'Plugins/'.$data['name'].'/config.php';
        $form_data = common_trans_plugin_config_data($config);

        if($data && $data['config'] != ''){
            $info = unserialize($data['config']); // 当前配置
        }else{
            $info = $form_data['info']; // 默认配置(一般不用，因为安装插件时已经将默认配置保存到 $data['config'] )
        }
        $info['id'] = $id;
        $data_list = $form_data['item_list'];
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle($data['title'].'配置')
                ->setPostUrl(U('admin/admin/update_plugin_config'))
                ->setItemsData($data_list)  
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
}