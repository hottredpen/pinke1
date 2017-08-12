<?php 
namespace Plugins\CmsProjectYuji\Controller;
class ProductController extends FrontBaseController {

    protected function _initialize() {
        parent::_initialize();

        // 首页产品
        $map['p.recommend_index_product'] = 1;
        $map['p.status']                  = 1;
        $recommend_index_product_products = D('Cms/CmsProduct','Datamanager')->getData(1,4,$map);
        $this->assign('recommend_index_product_products',$recommend_index_product_products);

        
    }


    public function _before_index(){
		$p         = I('p',1,'intval');
		$page_size = 10;
        $map = $this->_product_map();
    	$product_data = D('Cms/CmsProduct','Datamanager')->getData($p,$page_size,$map);
        $product_num  = D('Cms/CmsProduct','Datamanager')->getNum($map);
        $this->assign('pager',$this->_pager($product_num,$page_size));
    	$this->assign('product_data',$product_data);
    }

    private function _product_map(){
        $keywords = I('keywords','','common_filter_keywords');
        if($keywords != ''){
            $map['p.title'] = array('like','%'.$keywords.'%');
        }
        $map['p.status']      = 1;
        return $map;
    }

    public function _before_umbrella(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map['p.category_id'] = 1;
        $map['p.status']      = 1;
        $product_data = D('Cms/CmsProduct','Datamanager')->getData($p,$page_size,$map);
        $product_num  = D('Cms/CmsProduct','Datamanager')->getNum($map);
        $this->assign('pager',$this->_pager($product_num,$page_size));
        $this->assign('product_data',$product_data);
    }

    public function _before_bumbersoll(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map['p.category_id'] = 3;
        $map['p.status']      = 1;
        $product_data = D('Cms/CmsProduct','Datamanager')->getData($p,$page_size,$map);
        $product_num  = D('Cms/CmsProduct','Datamanager')->getNum($map);
        $this->assign('pager',$this->_pager($product_num,$page_size));
        $this->assign('product_data',$product_data);
    }

    public function _before_allweather(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map['p.category_id'] = 5;
        $map['p.status']      = 1;
        $product_data = D('Cms/CmsProduct','Datamanager')->getData($p,$page_size,$map);
        $product_num  = D('Cms/CmsProduct','Datamanager')->getNum($map);
        $this->assign('pager',$this->_pager($product_num,$page_size));
        $this->assign('product_data',$product_data);
    }

    public function detail(){
        $id = I('id',0,'intval');

        $product_info = D('Cms/CmsProduct','Datamanager')->getInfo($id);
        $this->assign('product_info',$product_info);
        seo_init(array(
            'info_title'       => $product_info['title'],
            'info_keywords'    => $product_info['keywords'],
            'info_description' => cutstr_html($product_info['description'].htmlspecialchars_decode($product_info['content']),150)
        ));
        M("cms_product")->where(array("id"=>$id))->setInc("view");
        $this->layoutDisplay("Plugins://"."detail");
    }



    public function productQRcode(){
        $url = I("url",'','trim');
        // $str = "http://cms.lankuwangluo.com/"."shop/".$id;
        $str = $url;
        $errorCorrectionLevel = "H";
        $matrixPointSize = "5";
        $padding = 0;
        \Common\Util\QRcode\QRcode::png($str,false,$errorCorrectionLevel,$matrixPointSize,$padding,true);
    }

}