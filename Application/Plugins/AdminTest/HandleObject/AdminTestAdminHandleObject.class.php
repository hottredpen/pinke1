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
        define('PK_ADMIN_HANDLEOBJECT','AdminTest');
        $this->uid = (int)$uid;
    }

    public function createAdminTest(){

        $model = D('Plugins://AdminTest/AdminTest');
        $post['module_name']       = $_POST['_pk_module_name'];
        $post['model_name']        = $_POST['_pk_model_name'];
        $post['sence_id']          = $_POST['_pk_sence_id'];
        $post['group_id']          = $_POST['_pk_group_id'];
        $post['handle_object']     = $_POST['_pk_handle_object'];
        $post['handle_action']     = $_POST['_pk_handle_action'];
        $post['is_plugin']         = $_POST['_pk_is_plugin'];
        $post['success_post_data'] = $this->_success_post_data($_POST); // todo剔除无用的
        $_POST = null;
        if (!$model->field('module_name,model_name,sence_id,group_id,handle_object,handle_action,is_plugin,success_post_data')->create($post,11)){
            return array("error"=>1,"info"=>$model->getError());
        }
        // test
        $res        = $model->add();
        if($res){
            return array("error"=>0,"info"=>'添加测试2成功','id'=>$res);
        }else{
            return array("error"=>1,"info"=>'添加测试失败');
        }
    }

    public function updateAdminTest(){
        $id    = I('_pk_id',0,'intval');

        $model = D('Plugins://AdminTest/AdminTest');
        $post['module_name']       = $_POST['_pk_module_name'];
        $post['model_name']        = $_POST['_pk_model_name'];
        $post['sence_id']          = $_POST['_pk_sence_id'];
        $post['group_id']          = $_POST['_pk_group_id'];
        $post['handle_object']     = $_POST['_pk_handle_object'];
        $post['handle_action']     = $_POST['_pk_handle_action'];
        $post['is_plugin']         = $_POST['_pk_is_plugin'];
        $post['success_post_data'] = $this->_success_post_data($_POST); // todo剔除无用的
        $_POST = null;
        if (!$model->field('id,module_name,model_name,sence_id,group_id,handle_object,handle_action,is_plugin,success_post_data')->create($post,12)){
            return array("error"=>1,"info"=>$model->getError());
        }
        // test
        $res = $model->where(array('id'=>$id))->save();
        // test_detail


        if($res){
            return array("error"=>0,"info"=>'修改测试成功');
        }else{
            return array("error"=>1,"info"=>'修改测试失败');
        }
    }

    private function _success_post_data($post){
        unset($post['_pk_module_name']);
        unset($post['_pk_handle_object']);
        unset($post['_pk_handle_action']);
        unset($post['form_token']); // todo form_token 也改为_pk_form_token
        unset($post['backurl']); // todo backurl 也改为_pk_backurl
        return serialize($post);
    }

    public function checkAssertData($assert_data_origin){
        // return array("error"=>0,"info"=>"保存成功");

        dump($assert_data_origin);



        $post_data['assert_data_origin'] = $assert_data_origin;
        // $componentsModel = D("Plugins://AdminTest/CmsProjectPinkeComponents"); // todo 

        $model = D('Test','Test');
        if (!$model->field('assert_data_origin')->create($post_data,11)){
            return array("error"=>1,"info"=>$model->getError());
        }
        // $post_data = $componentsModel->getPostData();


        // $res          = M('weixin_card')->add($base_info);
        // $res_member   = $componentsModel->add($member_data); // 所有的验证已经在这里
        // $res_advanced = M('weixin_card_advanced')->add($advanced_info);
        // if( $res && $res_member && $res_advanced ){
        //     return array("error"=>0,"info"=>"保存成功");
        // }else{
        //     return array("error"=>1,"info"=>"保存失败");
        // }
    }
}