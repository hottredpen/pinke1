<?php 
namespace Plugins\CmsProjectYuji\Controller;
class NewsController extends FrontBaseController {

    protected function _initialize() {
        parent::_initialize();
    }

    public function searchlist(){
    	$p = I('p',1,'intval');
    	$page_size = 10;
    	$map = $this->_search_map();
        $product_data = D('Cms/CmsDocument','Datamanager')->getDocumentData($p,$page_size,$map);
        $product_num  = D('Cms/CmsDocument','Datamanager')->getDocumentNum($map);
        $this->assign('list',$product_data);
		$this->layoutDisplay("Plugins://"."searchlist");
    }

    private function _search_map(){
    	$keywords = I('keywords','','common_filter_keywords');
    	$map      = array();
        if($keywords != ''){
            $map['d.title'] = array('like','%'.$keywords.'%');
        }
        $map['d.status']      = 1;
        $map['d.category_id'] = array("in",'177,194');
        return $map;
    }


}