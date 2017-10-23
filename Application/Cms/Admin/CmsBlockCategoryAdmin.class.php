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

class CmsBlockCategoryAdmin extends CmsBaseAdmin{
    public function _initialize() {
        parent::_initialize();
    }

    public function block_category(){



        $right_button['no'][0]['title']     = '编辑';
        $right_button['no'][0]['attribute'] = 'class="J_layer_dialog label label-primary" href="javascript:;" data-url="/Admin/Cms/editCmsBlockCategory/id/__id__" data-width="600px" data-height="400px" data-title="编辑-区块分类"';

        $right_button['no'][1]['title']     = '删除';
        $right_button['no'][1]['attribute'] = 'class="J_confirmurl label label-danger" href="javascript:;" data-uri="/Admin/Cms/deleteCmsBlockCategory"  data-id="__id__" data-msg="确定要删除id=__id__的列表项吗？"';


        $data_list = M("cms_block_category")->order('sort desc')->select();
        $tree      = new \Common\Util\Tree();
        $data_list = $tree->toFormatTree($data_list,'title');
        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('区块分类列表')
                ->ajax_url(U('Admin/Cms/ajaxCmsBlockCategory'))
                ->addTopButton('layer',array('data-action'=>'addCmsBlockCategory','data-width'=>"600px",'data-height'=>'400px','data-title'=>'新增-区块分类'))
                ->setTabNav($tab_list, $group)
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title_format', '分类名称')
                ->addTableColumn('name', '标示')
                ->addTableColumn('is_page', '是否是单页')
                ->addTableColumn('sort', '排序','ajax_edit')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->addRightButton('custom',array('title'=>'查看内容','href'=>U('admin/Cms/block',array('_filter'=>'category_id','_filter_content'=>'__id__'))))

                ->addRightButton('layer',array('data-action'=>'editCmsBlockCategory','data-width'=>"600px",'data-height'=>'400px','data-title'=>'编辑-区块分类'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteCmsBlockCategory','data-itemname'=>'区块分类'))
                ->alterTableData(
                    array('key' => 'pid', 'value' => '0'),
                    array('right_button' => $right_button)
                )
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addCmsBlockCategory(){
        $id          = I('id',0,'intval');
        $info['pid'] = $id;
        $builder     = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增区块分类')
                ->setPostUrl(U('Admin/Cms/createCmsBlockCategory'))
                ->addFormItem('pid', 'select', '上级分类','上级分类',select_list_as_tree('cms_block_category', 'title', array(0=>'作为一级分类'), 'id'))
                ->addFormItem('title', 'text', '分类名称')
                ->addFormItem('name', 'text', '分类标示')
                ->addFormItem('is_page', 'radio', '数据类型','数据类型',array(0=>'列表数据',1=>'单页数据'))
                ->addFormItem('status', 'radio', '是否启用','是否启用',array(1=>'启用',0=>'不启用'))
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editCmsBlockCategory(){
        $id      = I('id',0,'intval');
        $info    = M('cms_block_category')->where(array('id'=>$id))->find();
        $builder = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改区块分类')
                ->setPostUrl(U('Admin/Cms/saveCmsBlockCategory'))
                ->addFormItem('pid', 'select', '上级分类','上级分类',select_list_as_tree('cms_block_category', 'title', array(0=>'作为一级分类'), 'id'))
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