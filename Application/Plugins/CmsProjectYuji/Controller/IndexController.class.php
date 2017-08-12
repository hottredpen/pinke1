<?php 
namespace Plugins\CmsProjectYuji\Controller;
class IndexController extends FrontBaseController {

    protected function _initialize() {
        parent::_initialize();
    }

    public function _before_index(){
    	// 首页新品
		$map['p.recommend_index_new'] = 1;
		$map['p.status']              = 1;
        $recommend_index_new_products = D('Cms/CmsProduct','Datamanager')->getData(1,4,$map);
	   	$this->assign('recommend_index_new_products',$recommend_index_new_products);

        // 首页产品
        $map['p.recommend_index_product'] = 1;
        $map['p.status']                  = 1;
        $recommend_index_product_products = D('Cms/CmsProduct','Datamanager')->getData(1,4,$map);
        $this->assign('recommend_index_product_products',$recommend_index_product_products);

    }

}