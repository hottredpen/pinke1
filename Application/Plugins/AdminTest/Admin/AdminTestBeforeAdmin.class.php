<?php 
namespace Plugins\AdminTest\Admin;

class AdminTestBeforeAdmin extends AdminTestBaseController {

    protected function _initialize(){
        parent::_initialize();
    }

    public function createAdminTestBefore(){
        $AdminTestAdminHandleObject = $this->visitor->AdminTestAdminHandleObject();
        $res = $AdminTestAdminHandleObject->createAdminTestBefore();
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info']);
        }else{
            $this->pk_error($res['info']);
        }
    }



}