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

class CmsProductAdmin extends CmsBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function index(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $map          = D('Cms/CmsProduct','Datamanager')->replaceMap($map);
        $data_list    = D("Cms/CmsProduct","Datamanager")->getData($p,$page_size,$map,$order);
        $data_num     = D('Cms/CmsProduct','Datamanager')->getNum($map);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('产品列表')
                ->setSearch(array('title'=>'产品名称','price'=>'价格'),'',U('admin/cms/product'))
                ->ajax_url(U('Cms/ajaxCmsProduct'))
                ->addTopButton('custom',array('title'=>'新增','href'=>U('Cms/addCmsProduct')))
                ->addOrder('id,title,category_id,price,recommend_index_new,recommend_index_product,status')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '产品名称')
                ->addTableColumn('category_id', '产品分类','function','cms_local_product_category_id_title')
                ->addTableColumn('price', '价格')
                ->addTableColumn('cover_id_format', '封面图','cpk_pic')
                ->addTableColumn('recommend_index_new', '是否推荐到首页新品','status')
                ->addTableColumn('recommend_index_product', '是否推荐详情页','status')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('custom',array('title'=>'修改','href'=>U('Cms/editCmsProduct',array('id'=>'__id__'))))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteCmsProduct','data-itemname'=>'产品'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addCmsProduct(){
        $info    = array();
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增产品')
                ->setPostUrl(U('Cms/addCmsProduct'))
                ->setPostBackUrl(U('Cms/product'))
                ->addFormItem('category_id', 'select', '产品分类','',select_list_as_tree('cms_product_category', 'title',array(), 'id','id asc'))
                ->addFormItem('title', 'text', '产品标题')
                ->addFormItem('price', 'number', '价格')
                ->addFormItem('content', 'ueditor', '详细内容','',array('width'=>'100%'))
                ->addFormItem('keywords', 'text', '关键字（seo）')
                ->addFormItem('description', 'textarea', '产品概要（seo）')
                ->addFormItem('cover_ids_format', 'cpk_pictures', '所有封面图','所有封面图',array(),array('uploadtype'=>'yuji_product_cover','inputname'=>'cover_ids'))
                ->addFormItem('list_p_data', 'textarea', '列表显示数据')
                ->addFormItem('url_taobao', 'text', '产品淘宝链接')
                ->addFormItem('url_weixin', 'text', '产品微信链接')
                ->addFormItem('status', 'radio', '发布', '发布', array('1' => '是','0' => '否'))
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editCmsProduct(){
        $id                       = I('id',0,'intval');
        $info                     = D('Cms/CmsProduct','Datamanager')->getInfo($id,array('egt',0));
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增产品')
                ->setPostUrl(U('Cms/saveCmsProduct'))
                ->setPostBackUrl(U('Cms/product'))
                ->addFormItem('category_id', 'select', '产品分类','',select_list_as_tree('cms_product_category', 'title',array(), 'id','id asc'))
                ->addFormItem('title', 'text', '产品标题')
                ->addFormItem('price', 'number', '价格')
                ->addFormItem('content', 'ueditor', '详细内容','',array('width'=>'100%'))
                ->addFormItem('keywords', 'text', '关键字（seo）')
                ->addFormItem('description', 'textarea', '产品概要（seo）')
                ->addFormItem('cover_ids_format', 'cpk_pictures', '所有封面图','所有封面图',array(),array('uploadtype'=>'yuji_product_cover','inputname'=>'cover_ids'))
                ->addFormItem('list_p_data', 'textarea', '列表显示数据')
                ->addFormItem('url_taobao', 'text', '产品淘宝链接')
                ->addFormItem('url_weixin', 'text', '产品微信链接')
                ->addFormItem('status', 'radio', '发布', '发布', array('1' => '是','0' => '否'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

}