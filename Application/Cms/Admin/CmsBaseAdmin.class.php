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
namespace Cms\Admin;
use Admin\Controller\BackController;
/**
 * cms模块基础
 */
class CmsBaseAdmin extends BackController {

    protected function _initialize(){
        parent::_initialize();
    }

    public function setting(){
        $info     = D('Cms/CmsSetting','Datamanager')->getInfo();
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('cms配置设置')
                ->setFormColClass('col-md-8')
                ->setPostUrl(U('Cms/saveCmsSetting'))
                ->addFormItem('site_name', 'text', '网站名称','使用{:C("CMS_SITE_NAME")}')
                ->addFormItem('site_400', 'text', '400电话','使用{:C("CMS_SITE_400")}')
                ->addFormItem('site_com', 'text', '公司名称','使用{:C("CMS_SITE_COM")}')
                ->addFormItem('site_com_address', 'text', '公司地址','使用{:C("CMS_SITE_COM_ADDRESS")}')
                ->addFormItem('site_copyright', 'text', '版权','使用{:C("CMS_SITE_COPYRIGHT")}')
                ->addFormItem('site_icp', 'text', 'icp备案号','使用{:C("CMS_SITE_ICP")}')
                ->addFormItem('site_qq', 'text', 'QQ会话的链接','使用{:C("CMS_SITE_QQ")}')
                ->addFormItem('site_email', 'text', '邮箱','使用{:C("CMS_SITE_EMAIL")}')
                ->addFormItem('site_zipcode', 'text', '邮政编码','使用{:C("CMS_SITE_ZIPCODE")}')
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function saveCmsSetting(){
        $CmsAdminHandleObject = $this->visitor->CmsAdminHandleObject();
        $res = $CmsAdminHandleObject->saveCmsSetting();
        if($res['error']==0 && $res['info'] != ""){
            $this->cpk_success($res['info'].",刷新缓存后生效",array('backurl'=>U('admin/cache/index')));
        }else{
            $this->cpk_error($res['info']);
        }
    }


}