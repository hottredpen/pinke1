<?php
// +----------------------------------------------------------------------
// | 品客PHP框架 [ pinkePHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 浙江蓝酷网络科技有限公司 [ http://www.lankuwangluo.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.pinkephp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace Admin\Controller;

class SettingController extends BackController {

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $group     = I('group','common','trim');

        $tab_list  = D('Admin/AdminModule','Datamanager')->getSettingTabByGroup($group);
        $form_data = D('Admin/AdminConfig','Datamanager')->getSettingFormDataByGroup($group);

        $data_list = $form_data['item_list'];
        $info      = $form_data['info'];

        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')
                ->setFormColClass('col-md-12')
                ->SetTabNav($tab_list['data'],$tab_list['key'])
                ->setPostUrl(U('Setting/saveSetting'))
                ->setItemsData($data_list)  
                ->setFormData($info)
                ->addFormItem('group', 'hidden')
                ->setItemToGroup('mail_address,mail_loginname,mail_smtp,mail_password,mail_port,mail_name',3,'邮件配置',array('md_l'=>3,'md_r'=>7),'col-sm-12','col-sm-0') // 目前直接指定组，有时间再自动
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function saveSetting(){
        $AdminAdminHandleObject = $this->visitor->AdminAdminHandleObject();
        $res = $AdminAdminHandleObject->saveSetting();
        if($res['error']==0 && $res['info'] != ""){
            F('WEB_SETTING',null);  // @todo 用函数清理
            $this->cpk_success($res['info']);
        }else{
            $this->cpk_error($res['info']);
        }
    }
}