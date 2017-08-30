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
 * banner管理
 */
class CmsBannerAdmin extends CmsBaseAdmin{
    public function _initialize() {
        parent::_initialize();
    }

    public function block(){
        $p            = I('p',1,'intval');
        $page_size    = 10;
        $map          = $this->getMap();
        $order        = $this->getOrder();

        $map          = D('Cms/CmsBanner','Datamanager')->replaceMap($map);
        $data_list    = D("Cms/CmsBanner","Datamanager")->getData($p,$page_size,$map,$order);
        $data_num     = D('Cms/CmsBanner','Datamanager')->getNum($map);

        $builder      = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('广告列表')
                ->setSearch(array('title'=>'内容名称'),'',U('admin/cms/block'))
                ->ajax_url(U('Cms/ajaxCmsBanner'))
                ->addTopButton('custom',array('title'=>'新增','href'=>U('Admin/Cms/addCmsBanner')))
                ->addOrder('id,title,category_id,status')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '区块名称')
                ->addTableColumn('category_id', '区块分类','function','cms_local_block_category_id_title')
                ->addTableColumn('cover_id_format', '封面图','cpk_pic')
                ->addTableColumn('orderid', '排序','ajax_edit')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('custom',array('title'=>'修改','href'=>U('Cms/editCmsBanner',array('id'=>'__id__'))))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteCmsBanner','data-itemname'=>'区块内容'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');

    }

    public function addCmsBanner(){
        $info    = array();
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增碎片块')
                ->setPostUrl(U('Admin/Cms/createCmsBanner'))
                ->setPostBackUrl(U('Cms/block'))
                ->addFormItem('category_id', 'select', '分类','',select_list_as_tree('cms_block_category', 'title',array(), 'id','id asc'))
                ->addFormItem('title', 'text', '标题')
                ->addFormItem('list_p_data', 'textarea', '列表显示数据')
                ->addFormItem('otherdata', 'textarea', '其他数据')
                ->addFormItem('cover_ids_format', 'cpk_pictures', '封面图','封面图',array(),array('uploadtype'=>'cms_block_cover','inputname'=>'cover_ids'))
                ->addFormItem('status', 'radio', '发布', '发布', array('1' => '是','0' => '否'))
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
    public function editCmsBanner(){
        $id                       = I('id',0,'intval');
        $info                     = D('Cms/CmsBanner','Datamanager')->getInfo($id);
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改碎片块')
                ->setPostUrl(U('Admin/Cms/saveCmsBanner'))
                ->setPostBackUrl(U('Cms/block'))
                ->addFormItem('category_id', 'select', '分类','',select_list_as_tree('cms_block_category', 'title',array(), 'id','id asc'))
                ->addFormItem('title', 'text', '标题')
                ->addFormItem('list_p_data', 'textarea', '列表显示数据')
                ->addFormItem('otherdata', 'textarea', '其他数据')
                ->addFormItem('cover_ids_format', 'cpk_pictures', '封面图','封面图',array(),array('uploadtype'=>'cms_block_cover','inputname'=>'cover_ids'))
                ->addFormItem('status', 'radio', '发布', '发布', array('1' => '是','0' => '否'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

}