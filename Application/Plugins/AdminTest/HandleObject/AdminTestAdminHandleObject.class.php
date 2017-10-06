<?php
namespace Plugins\AdminTest\HandleObject;
use Admin\HandleObject\BaseHandleObject;
/**
 * 管理员操作对象
 */
class AdminTestAdminHandleObject extends BaseHandleObject {
    protected $uid;
    function __construct($uid=0) {
        parent::__construct($uid);
        C('CPK_FROM_MODULE_ADMIN',1);
        $this->uid = (int)$uid;
    }

    public function addCmsProjectPinkeComponents(){
        $componentsModel = D("Plugins://CmsProjectPinke/CmsProjectPinkeComponents");
        if (!$componentsModel->field('name,title,info,content')->create($_POST,11)){
            return array("error"=>1,"info"=>$componentsModel->getError());
        }
        $post_data = $componentsModel->getPostData();


        $res          = M('weixin_card')->add($base_info);
        $res_member   = $componentsModel->add($member_data); // 所有的验证已经在这里
        $res_advanced = M('weixin_card_advanced')->add($advanced_info);
        if( $res && $res_member && $res_advanced ){
            return array("error"=>0,"info"=>"保存成功");
        }else{
            return array("error"=>1,"info"=>"保存失败");
        }
    }
}