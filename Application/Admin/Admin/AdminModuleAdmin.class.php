<?php 
namespace Admin\Admin;

class AdminModuleAdmin extends AdminBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function module(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Admin/AdminModule','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Admin/AdminModule','Datamanager')->getNum($map);

        // 未安装时的按钮
        $right_button['no'][0]['title']     = '安装';
        $right_button['no'][0]['attribute'] = 'class="label label-success" href='.U('admin/admin/before_install_module',array('name'=>'__name__')).'';


        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('模块列表')
                ->SetTabNav(array(
                        array('title'=>'本地','href'=>'javascript:;'),
                    ),0)
                ->setSearch(array('title'=>'模块名称'),'',U('admin/admin/module'))
                ->addOrder('last_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '模块名称')
                ->addTableColumn('version', '版本号')
                ->addTableColumn('author', '作者')
                ->addTableColumn('description', '简介')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('custom',array('href'=>U('admin/__name__/index'),'class'=>'label label-success','title'=>'管理'))
                ->addRightButton('custom',array('href'=>U('admin/admin/plugin_config',array('id'=>'__id__')),'class'=>'label label-primary','title'=>'设置'))
                ->addRightButton('custom',array('href'=>U('admin/admin/before_uninstall_module',array('id'=>'__id__')),'title'=>'卸载','class'=>'label label-danger'))
                ->alterTableData(
                    array('key' => 'status', 'value' => '-1'),
                    array('right_button' => $right_button)
                )
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function before_install_module(){
        $name       = I('name','','trim');
        $info['name']  = $name;
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('安装模块')
                ->setPostUrl(U('Admin/Admin/install_module'))
                ->addFormItem('is_backup_db', 'radio', '覆盖数据库', '', array('1' => '覆盖','0' => '不覆盖'))
                ->setFormData($info)
                ->addFormItem('name', 'hidden', 'name', '')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function before_uninstall_module(){
        $id       = I('id',0,'intval');
        $info['id']  = $id;
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('卸载模块')
                ->setPostUrl(U('Admin/Admin/uninstall_module'))
                ->addFormItem('is_backup_db', 'radio', '备份数据库', '', array('1' => '备份','0' => '不备份'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function install_module(){
        $AdminAdminHandleObject = $this->visitor->AdminAdminHandleObject();
        $res = $AdminAdminHandleObject->installModule();
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info'],array('backurl'=>U('Admin/Admin/module')));
        }else{
            $this->pk_error($res['info']);
        }
    }

    public function uninstall_module(){
        $AdminAdminHandleObject = $this->visitor->AdminAdminHandleObject();
        $res = $AdminAdminHandleObject->uninstallModule();
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info'],array('backurl'=>U('Admin/Admin/module')));
        }else{
            $this->pk_error($res['info']);
        }
    }

}