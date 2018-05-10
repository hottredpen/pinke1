<?php 
namespace Plugins\AdminTest\Admin;

class AdminTestAdmin extends AdminTestBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }
    /**
     * [当前模块的本地测试]
     *
     * @author hottredpen
     * @date   2018-03-29
     * @return [type]
     */
    public function index(){
        $module_name = I('module_name','Admin','trim');
        $list        = glob(APP_PATH.$module_name.'/Test/*.class.php');

        $data_list   = D('Plugins://AdminTest/AdminTest','Datamanager')->getLocalTestDataByModelName($module_name);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('单元测试列表')
                ->SetTabNav(array(
                        array('title'=>'Admin','href'=>U('Admin/AdminTest/index',array('module_name'=>'Admin'))),
                        array('title'=>'Cms','href'=>U('Admin/AdminTest/index',array('module_name'=>'Cms'))),
                        array('title'=>'User','href'=>U('Admin/AdminTest/index',array('module_name'=>'User')))
                ))
                ->addOrder('last_time')
                ->addTableColumn('title', '模型名称')
                ->addTableColumn('model_name', '模型')
                ->addTableColumn('handle_object', '操作对象')
                ->addTableColumn('units_test', '单元测试数据配置')
                ->addTableColumn('groups_test', '集成测试数据配置')
                ->setTableDataList($data_list)
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');



    }

    public function admin_test_list(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Plugins://AdminTest/AdminTest','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Plugins://AdminTest/AdminTest','Datamanager')->getNum($map);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('测试列表')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('module_name', 'module_name')
                ->addTableColumn('handle_object', 'handle_object')
                ->addTableColumn('handle_action', 'handle_action')
                ->addTableColumn('model_name', 'model_name')
                ->addTableColumn('sence_id', 'sence_id')
                ->addTableColumn('group_id', 'group_id')
                ->addTableColumn('status', '测试状态', 'status')
                ->addTableColumn('is_init', '数据初始化', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('custom',array('title'=>'修改','href'=>U('admin/adminTest/editAdminTest',array('id'=>'__id__'))))
                ->addRightButton('custom',array('title'=>'测试','href'=>U('admin/adminTest/startAdminTest',array('id'=>'__id__'))))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdminTest','data-itemname'=>'测试'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addAdminTest(){

        $plugin_name = I('plugin_name','','trim'); // todo
        $module_name = I('module_name','','trim');
        $model_name  = I('model_name','','trim');
        $sence_id    = I('sence_id',11,'intval');
        $group_id    = I('group_id',0,'intval');

        if($pk_plugin_name != ''){
            $Plugins = "Plugins://";
        }else{
            $Plugins = "";
        }
        $modelTest       = D($Plugins.$module_name.'/'.$model_name,'Test');

        $formPostItems  = $modelTest->formPostItems($sence_id,$group_id);
        $info           = $modelTest->getPostDefaultValue();
        $info['_pk_is_plugin']      = $Plugins == "" ? 0 : 1;

        $info['_pk_module_name']    = $module_name;
        $info['_pk_model_name']     = $model_name;
        $info['_pk_sence_id']       = $sence_id;
        $info['_pk_group_id']       = $group_id;
        $info['_pk_handle_object']  = $modelTest->getHandleObject();
        $info['_pk_handle_action']  = $modelTest->getHandleAction($sence_id);
        $info['_pk_is_plugin']      = $modelTest->getIsPlugin();

        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('调整测试数据')
                ->setPostUrl(U('Admin/AdminTest/createAdminTest'))
                ->setBackUrl(U('admin/AdminTest/admin_test_list'))
                ->setItemsData($formPostItems)
                ->setFormData($info)
                ->addFormItem("_pk_module_name",'hidden')
                ->addFormItem("_pk_model_name",'hidden')
                ->addFormItem("_pk_sence_id",'hidden')
                ->addFormItem("_pk_group_id",'hidden')
                ->addFormItem("_pk_handle_object",'hidden')
                ->addFormItem("_pk_handle_action",'hidden')
                ->addFormItem("_pk_is_plugin",'hidden')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editAdminTest(){
        $id = I('id',0,'intval');

        $test_info      = D('Plugins://AdminTest/AdminTest','Datamanager')->getInfo($id);

        $Plugins        = $test_info['is_plugin'] ? 'Plugins://' : '';
        $module_name    = $test_info['module_name'];
        $model_name     = $test_info['model_name'];
        $sence_id       = $test_info['sence_id'];
        $group_id       = $test_info['group_id'];
        $handle_object  = $test_info['handle_object'];
        $handle_action  = $test_info['handle_action'];

        $modelTest      = D($Plugins.$module_name.'/'.$model_name,'Test');

        $formPostItems  = $modelTest->formPostItems($sence_id,$group_id);

        // dump($info);
        // exit();
        $info     = unserialize($test_info['success_post_data']);
        $info['_pk_module_name']   = $module_name;
        $info['_pk_model_name']    = $model_name;
        $info['_pk_sence_id']      = $sence_id;
        $info['_pk_group_id']      = $group_id;
        $info['_pk_handle_object'] = $handle_object;
        $info['_pk_handle_action'] = $handle_action;
        $info['_pk_is_plugin']     = $test_info['is_plugin'];
        $info['_pk_id']            = $id;

        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('调整测试数据')
                ->setPostUrl(U('Admin/AdminTest/updateAdminTest'))
                ->setBackUrl(U('admin/AdminTest/admin_test_list'))
                ->setItemsData($formPostItems)
                ->setFormData($info)
                ->addFormItem("_pk_module_name",'hidden')
                ->addFormItem("_pk_model_name",'hidden')
                ->addFormItem("_pk_sence_id",'hidden')
                ->addFormItem("_pk_group_id",'hidden')
                ->addFormItem("_pk_handle_object",'hidden')
                ->addFormItem("_pk_handle_action",'hidden')
                ->addFormItem("_pk_is_plugin",'hidden')
                ->addFormItem("_pk_id",'hidden')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');

    }

    public function startAdminTest(){
        $id = I('id',0,'intval');

        $test_info      = D('Plugins://AdminTest/AdminTest','Datamanager')->getInfo($id);

        $Plugins        = $test_info['is_plugin'] ? 'Plugins://' : '';
        $module_name    = $test_info['module_name'];
        $model_name     = $test_info['model_name'];
        $sence_id       = $test_info['sence_id'];
        $group_id       = $test_info['group_id'];
        $handle_object  = $test_info['handle_object'];
        $handle_action  = $test_info['handle_action'];

        $modelTest      = D($Plugins.$module_name.'/'.$model_name,'Test');

        $test_data  = $modelTest->getUnitTestData($sence_id,$group_id,$id);

        $this->assign('test_data',$test_data);
        $this->assign('id',$id);
        $this->assign('uuid',$uuid);
        $this->theme('one')->admindisplay('Plugins://AdminTestData/testlist');
        
    }

    public function init_post_data(){
        $id = I('id',0,'intval');
        $test_info = D('Plugins://AdminTest/AdminTest','Datamanager')->getInfo($id);

        $success_post_data = unserialize($test_info['success_post_data']);

        $is_init_all_success = 1;
        foreach ($success_post_data as $key => $value) {
            $success_post_data[$key] = admin_test_format_field_var($value);
            if(!admin_test_is_not_has_var($success_post_data[$key])){
                $is_init_all_success = 0;
            }
        }
        $res = M('admin_test')->where(array('id'=>$test_info['id']))->setField(
            array(
                'success_post_data' => serialize($success_post_data),
                'is_init'           => $is_init_all_success,
                'update_time'       => time()
            ));
        if($res && $is_init_all_success){
            $this->pk_success('初始化数据成功');
        }else{
            $this->pk_error('初始化数据失败');
        }


    }


}