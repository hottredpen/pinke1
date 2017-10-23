<?php

namespace Admin\Controller;

class PluginController extends BackController {

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Admin/AdminPlugin','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Admin/AdminPlugin','Datamanager')->getNum($map);

        // 未安装时的按钮
        $right_button['no'][0]['title']     = '安装';
        $right_button['no'][0]['attribute'] = 'class="label label-success" href='.U('admin/plugin/install',array('name'=>'__name__')).'';


        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('管理员列表')
                ->SetTabNav(array(
                        array('title'=>'本地','href'=>'javascript:;'),
                    ),0)
                ->setSearch(array('title'=>'插件名称'),'',U('admin/admin/index'))
                ->ajax_url(U('Admin/ajaxAdmin'))
                ->addOrder('last_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '插件名称')
                ->addTableColumn('version', '版本号')
                ->addTableColumn('author', '作者')
                ->addTableColumn('description', '简介')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('custom',array('href'=>U('admin/__name__/setting'),'class'=>'label label-primary','title'=>'设置'))
                ->addRightButton('delete_confirm',array('data-uri'=>U('admin/plugin/uninstall',array('name'=>'__name__')),'data-itemname'=>'管理员','title'=>'卸载','data-msg'=>'确定进行此操作?'))
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
    public function install(){
        $plugin_name = I('name','','trim');

        $plugin_class = common_get_plugin_class($plugin_name);
        if (!class_exists($plugin_class)) {
            $this->error('插件不存在！');
        }
        // 实例化插件
        $plugin = new $plugin_class;
        // 插件预安装
        if(!$plugin->install()) {
            return $this->error('插件预安装失败!原因：'. $plugin->getError());
        }
        // 安装插件
        $res_plugin = D('Admin/AdminPlugin','Service')->install($plugin_name,$plugin->info,$plugin->hooks,$plugin->database_prefix);
        if($res_plugin['error'] == 1){
            $this->error($res_plugin['info']);
        }
        $this->cpk_success('插件安装成功',array('backurl'=>U('admin/plugin/index')));
    }

    /**
     * 卸载插件
     */
    public function uninstall($name = ''){
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
            return $this->error('插件预卸载失败!原因：'. $plugin->getError());
        }
        // 卸载插件
        $res_plugin = D('Admin/AdminPlugin','Service')->uninstall($plugin_name,$plugin->database_prefix);
        if($res_plugin['error'] == 1){
            $this->error($res_plugin['info']);
        }
        $this->cpk_success('插件卸载成功',array('backurl'=>U('admin/plugin/index')));

    }

}