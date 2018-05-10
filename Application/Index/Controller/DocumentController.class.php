<?php
namespace Index\Controller;
class DocumentController extends ApiBaseController {

    protected function _initialize() {
    	parent::_initialize();
    }


    public function about_info(){
        $info = D('Cms/CmsDocument','Datamanager')->getInfoByEnnameForApp('about_index');
        if(!$info){
            $res = array('error'=>400,'info'=>'无数据');
            $this->json_return($res);
        }else{
            $result['code']  = 200;
            $result['msg']   = "成功";
            $result['data']  = $info;
            $this->json($result);
        }
    }

}