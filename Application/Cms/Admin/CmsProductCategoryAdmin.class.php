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

class CmsProductCategoryAdmin extends CmsBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function index(){

        $data_list = M("cms_product_category")->order('sort desc')->select();
        $tree      = new \Common\Util\Tree();
        $data_list = $tree->toFormatTree($data_list,'title');
        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('产品分类列表')
                ->ajax_url(U('Cms/ajaxCmsProductCategory'))
                ->addTopButton('layer',array('data-action'=>'addCmsProductCategory','data-width'=>"600px",'data-height'=>'400px','data-title'=>'新增-产品分类'))
                ->addTopButton('batchdelete',array('data-uri'=>U('admin/cms/batchdeleteCmsProductCategory')))
                ->setTabNav($tab_list, $group)
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title_format', '分类名称')
                ->addTableColumn('name', '分类标示')
                ->addTableColumn('category_id', '分类')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->addRightButton('layer',array('data-action'=>'editCmsProductCategory','data-width'=>"600px",'data-height'=>'400px','data-title'=>'编辑-产品分类'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteCmsProductCategory','data-itemname'=>'产品分类'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addCmsProductCategory(){
        $id      = I('id',0,'intval');
        $info['pid'] = $id;
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增分类')
                ->setPostUrl(U('Cms/addCmsProductCategory'))
                ->addFormItem('pid', 'select', '上级分类','上级分类',select_list_as_tree('cms_product_category', 'title', array(0=>'作为一级分类'), 'id'))
                ->addFormItem('title', 'text', '分类名称')
                ->addFormItem('name', 'text', '分类标示')
                ->addFormItem('status', 'radio', '是否启用','是否启用',array(1=>'启用',0=>'不启用'))
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editCmsProductCategory(){
        $id      = I('id',0,'intval');
        $info    = M('cms_product_category')->where(array('id'=>$id))->find();
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改分类')
                ->setPostUrl(U('Cms/saveCmsProductCategory'))
                ->addFormItem('pid', 'select', '上级分类','上级分类',select_list_as_tree('cms_product_category', 'title', array(0=>'作为一级分类'), 'id'))
                ->addFormItem('title', 'text', '分类名称')
                ->addFormItem('name', 'text', '分类标示')
                ->addFormItem('status', 'radio', '是否启用','是否启用',array(1=>'启用',0=>'不启用'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

}