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

class CmsBannerCategoryAdmin extends CmsBaseAdmin{
    public function _initialize() {
        parent::_initialize();
    }

    public function block_category(){
        $data_list = M("cms_banner_category")->order('sort desc')->select();
        $tree      = new \Common\Util\Tree();
        $data_list = $tree->toFormatTree($data_list,'title');
        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('广告位列表')
                ->ajax_url(U('Admin/Cms/ajaxCmsBannerCategory'))
                ->addTopButton('layer',array('data-action'=>'addCmsBannerCategory','data-width'=>"600px",'data-height'=>'400px','data-title'=>'新增-区块分类'))
                ->setTabNav($tab_list, $group)
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title_format', '分类名称')
                ->addTableColumn('name', '标示')
                ->addTableColumn('sort', '排序','ajax_edit')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->addRightButton('layer',array('name'=>'add_sub_cate','title'=>'添加子分类','data-action'=>'addCmsBannerCategory','data-width'=>"600px",'data-height'=>'400px','data-title'=>'编辑-区块分类'))
                ->addRightButton('layer',array('data-action'=>'editCmsBannerCategory','data-width'=>"600px",'data-height'=>'400px','data-title'=>'编辑-区块分类'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteCmsBannerCategory','data-itemname'=>'区块分类'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addCmsBannerCategory(){
        $id          = I('id',0,'intval');
        $info['pid'] = $id;
        $builder     = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增区块分类')
                ->setPostUrl(U('Admin/Cms/createCmsBannerCategory'))
                ->addFormItem('pid', 'select', '上级分类','上级分类',select_list_as_tree('cms_block_category', 'title', array(0=>'作为一级分类'), 'id'))
                ->addFormItem('title', 'text', '分类名称')
                ->addFormItem('name', 'text', '分类标示')
                ->addFormItem('status', 'radio', '是否启用','是否启用',array(1=>'启用',0=>'不启用'))
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editCmsBannerCategory(){
        $id      = I('id',0,'intval');
        $info    = M('cms_block_category')->where(array('id'=>$id))->find();
        $builder = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改区块分类')
                ->setPostUrl(U('Admin/Cms/saveCmsBannerCategory'))
                ->addFormItem('pid', 'select', '上级分类','上级分类',select_list_as_tree('cms_block_category', 'title', array(0=>'作为一级分类'), 'id'))
                ->addFormItem('title', 'text', '分类名称')
                ->addFormItem('name', 'text', '分类标示')
                ->addFormItem('status', 'radio', '是否启用','是否启用',array(1=>'启用',0=>'不启用'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }


}