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
/**
 * 文章分类
 */
class CmsCategoryAdmin extends CmsBaseAdmin{

    public function _initialize() {
        parent::_initialize();
    }

    public function index(){
        $data_list = M("cms_category")->order('sort desc')->select();
        $tree      = new \Common\Util\Tree();
        $data_list = $tree->toFormatTree($data_list,'title');
        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('分类列表')
                ->ajax_url(U('Admin/Cms/ajaxCmsCategory'))
                ->addTopButton('layer',array('data-action'=>'addCmsCategory','data-width'=>"700px",'data-height'=>'450px','data-title'=>'新增-分类'))
                ->setTabNav($tab_list, $group)
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title_format', '分类名称')
                ->addTableColumn('name', '标示')
                ->addTableColumn('is_page', '是否是单页')
                ->addTableColumn('display_tpl', '模板名称','ajax_edit')
                ->addTableColumn('sort', '排序','ajax_edit')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->addRightButton('layer',array('name'=>'add_sub_cate','title'=>'添加子分类','data-action'=>'addCmsCategory','data-width'=>"700px",'data-height'=>'450px','data-title'=>'编辑-分类'))
                ->addRightButton('layer',array('data-action'=>'editCmsCategory','data-width'=>"700px",'data-height'=>'450px','data-title'=>'编辑-分类'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteCmsCategory','data-itemname'=>'分类'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addCmsCategory(){
        $id          = I('id',0,'intval');
        $info['pid'] = $id;
        
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增分类')
                ->setPostUrl(U('Admin/Cms/createCmsCategory'))
                ->addFormItem('pid', 'select', '上级分类','上级分类',select_list_as_tree('cms_category', 'title', array(0=>'作为一级分类'), 'id'))
                ->addFormItem('title', 'text', '分类名称')
                ->addFormItem('name', 'text', '分类标示')
                ->addFormItem('is_page', 'radio', '数据类型','数据类型',array(0=>'列表数据',1=>'单页数据'))
                ->addFormItem('status', 'radio', '是否启用','是否启用',array(1=>'启用',0=>'不启用'))
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editCmsCategory(){
        $id      = I('id',0,'intval');
        $info    = M('cms_category')->where(array('id'=>$id))->find();
        $builder = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改分类')
                ->setPostUrl(U('Admin/Cms/saveCmsCategory'))
                ->addFormItem('pid', 'select', '上级分类','上级分类',select_list_as_tree('cms_category', 'title', array(0=>'作为一级分类'), 'id'))
                ->addFormItem('title', 'text', '分类名称')
                ->addFormItem('name', 'text', '分类标示')
                ->addFormItem('is_page', 'radio', '数据类型','数据类型',array(0=>'列表数据',1=>'单页数据'))
                ->addFormItem('status', 'radio', '是否启用','是否启用',array(1=>'启用',0=>'不启用'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
}