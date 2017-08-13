<?php
namespace Plugins\CmsProjectYuji\Controller;
use Cms\Controller\CmsBaseController;

class FrontBaseController extends CmsBaseController {

    protected function _initialize() {
        parent::_initialize();
    }

    public function _empty($name){
        // 标示
        $page_code     = strtolower(CONTROLLER_NAME)."_".strtolower(ACTION_NAME);

        $page_catedata = M("cms_category")->where(array('name'=>array("like","%".$page_code."%")))->find();

        // _before
        $before_method = '_before_'.$name;
        // 检查是否存在方法$before_method
        if (method_exists($this, $before_method)) {
            $this->$before_method();
        }
        // cms_tag
        $id        = I('id',0,'intval');
        $p         = I('p',1,'intval');
        $page_size = 10;
        // $cms_tag['cid']       = $page_catedata['id'];
        $cms_tag['enname']    = $page_catedata['name'];
        $cms_tag['id']        = $id;
        $cms_tag['p']         = $p;
        $cms_tag['page_size'] = $page_size;

        $this->assign('cms_tag',$cms_tag);

        // tpl
        $tpl = $page_catedata['display_tpl'] != "" ? $page_catedata['display_tpl'] : $name;

        // 详情页
        if($id > 0){
            M("cms_document")->where(array("id"=>$id))->setInc("view");
            $tpl = $tpl."_info";
        }
        $this->layoutDisplay("Plugins://".$tpl);
    }
}