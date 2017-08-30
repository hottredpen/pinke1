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

class CmsSeoAdmin extends CmsBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function index(){

        $data_list    = M('cms_seo')->select();

        foreach ($data_list as $key => $value) {
            $data_list[$key]['url_format'] = $value['module']."/".$value['action']."/".$value['other'];
        }
        $tree      = new \Common\Util\Tree();
        $data_list = $tree->toFormatTree($data_list,'name');
        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('分类列表')
                ->ajax_url(U('Cms/ajaxSeo'))
                ->addTopButton('layer',array('data-action'=>'addCmsSeo','data-width'=>"700px",'data-height'=>'700px','data-title'=>'新增-SEO'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('name_format', '名称')
                ->addTableColumn('url_format', '链接')
                ->addTableColumn('title_tpl', '标题')
                ->addTableColumn('keywords_tpl', '关键字')
                ->addTableColumn('description_tpl', '描述')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->addRightButton('layer',array('data-action'=>'editCmsSeo','data-width'=>"700px",'data-height'=>'700px','data-title'=>'编辑-SEO'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteCmsSeo','data-itemname'=>'SEO'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }
    public function addCmsSeo(){
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增SEO')
                ->setPostUrl(U('Cms/createCmsSeo'))
                ->addFormItem('pid', 'select', '页面所属模块','',select_list_as_tree('cms_seo', 'name', array(0=>'作为新模块'), 'id','ordid desc'))
                ->addFormItem('name', 'text', '名称')
                ->addFormItem('module', 'text', '模块')
                ->addFormItem('action', 'text', '动作')
                ->addFormItem('other', 'text', '附加值')
                ->addFormItem('title_tpl', 'text', '标题')
                ->addFormItem('keywords_tpl', 'text', '关键字')
                ->addFormItem('description_tpl', 'text', '描述')
                ->addFormItem('status', 'radio', '是否启用','是否启用',array(1=>'启用',0=>'不启用'))
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
    public function editCmsSeo(){
        $id = I('id',0,'intval');
        $info    = M('cms_seo')->where(array('id'=>$id))->find();
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增SEO')
                ->setPostUrl(U('Cms/saveCmsSeo'))
                ->addFormItem('pid', 'select', '页面所属模块','',select_list_as_tree('cms_seo', 'name', array(0=>'作为新模块'), 'id','ordid desc'))
                ->addFormItem('name', 'text', '名称')
                ->addFormItem('module', 'text', '模块')
                ->addFormItem('action', 'text', '动作')
                ->addFormItem('other', 'text', '附加值')
                ->addFormItem('title_tpl', 'text', '标题')
                ->addFormItem('keywords_tpl', 'text', '关键字')
                ->addFormItem('description_tpl', 'text', '描述')
                ->addFormItem('status', 'radio', '是否启用','是否启用',array(1=>'启用',0=>'不启用'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

}