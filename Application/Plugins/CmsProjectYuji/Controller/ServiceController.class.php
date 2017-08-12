<?php 
namespace Plugins\CmsProjectYuji\Controller;
class ServiceController extends FrontBaseController {

    protected function _initialize() {
        parent::_initialize();
    }

    public function post(){
        $res = $this->_visitor_post();
        if($res['error'] == 0 && $res['info'] != ''){
            $this->cpk_success($res['info']);
        }else{
            $this->cpk_error($res['info']);
        }
    }

    private function _visitor_post(){
        $cmsPostModel = D('Cms/CmsServicePost');
        if (!$cmsPostModel->field('buyfrom,orderno,phone,message')->create($_POST,11)){
            return array("error"=>1,"info"=>$cmsPostModel->getError());
        }
        $res = $cmsPostModel->add();
        if($res){
            return array("error"=>0,"info"=>"留言成功，感谢您对我们语记提建议",'id'=>$res);
        }else{
            return array("error"=>1,"info"=>"添加失败");
        }
    }




}