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
 * 文档
 */
class CmsDocumentAdmin extends CmsBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function index(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();
        $map          = D('Cms/CmsDocument','Datamanager')->replaceMap($map);
        $data_list    = D('Cms/CmsDocument','Datamanager')->getDocumentData($p,$page_size,$map,$order);
        $data_num     = D('Cms/CmsDocument','Datamanager')->getDocumentNum($map);

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('文章列表')
                ->setSearch(array('title'=>'文章标题'),'',U('admin/cms/document'))
                ->ajax_url(U('Cms/ajaxCmsDocument'))
                ->addTopButton('custom',array('title'=>'新增','href'=>U('cms/addCmsDocument')))
                ->addOrder('id,title,category_id,recommend_side')
                ->addFilter('category_id','function','cms_local_category_id_name')
                ->addFilter('status','options',array(0=>'关闭',1=>'开启'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '文章标题')
                ->addTableColumn('category_id', '文章所在栏目','function','cms_local_category_id_name')
                ->addTableColumn('cover_id_format', '封面图','cpk_pic')
                ->addTableColumn('recommend_side', '推荐到右侧', 'status')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('custom',array('title'=>'修改','href'=>U('cms/editCmsDocument',array('id'=>'__id__'))))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteCmsDocument','data-itemname'=>'文章'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addCmsDocument(){

        $info    = array();

        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增文章')
                ->setFormColClass('col-md-10')
                ->setFormItemCol_xs_sm_md_lg(array('md_r'=>9))
                ->setPostUrl(U('Admin/Cms/createCmsDocument'))
                ->addFormItem('category_id', 'select', '文章分类','',select_list_as_tree('cms_category', 'title',array(), 'id','id asc'))
                ->addFormItem('title', 'text', '文章标题')
                ->addFormItem('content', 'ueditor', '详细内容','',array('width'=>'100%'))
                ->addFormItem('keywords', 'text', '关键字（seo）')
                ->addFormItem('description', 'textarea', '文章概要（seo）')
                ->addFormItem('cover_id', 'cover_config_by_category_id', '封面','',array('info_data'=>$info),array('trigger_from'=>'category_id','from_module'=>'cms'))
                ->addFormItem('list_p_data', 'textarea', '列表显示数据')
                ->addFormItem('status', 'radio', '发布', '发布', array('1' => '是','0' => '否'))
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function editCmsDocument(){
        $id              = I('id',0,'intval');
        $info            = D('Cms/CmsDocument','Datamanager')->getDocumentInfoData_id($id,array('egt',0));
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改文章')
                ->setFormColClass('col-md-10')
                ->setFormItemCol_xs_sm_md_lg(array('md_r'=>9))
                ->setPostUrl(U('Admin/Cms/saveCmsDocument'))
                ->addFormItem('category_id', 'select', '文章分类','',select_list_as_tree('cms_category', 'title',array(), 'id','id asc'))
                ->addFormItem('title', 'text', '文章标题')
                ->addFormItem('content', 'ueditor', '详细内容','',array('width'=>'100%'))
                ->addFormItem('keywords', 'text', '关键字（seo）')
                ->addFormItem('description', 'textarea', '文章概要（seo）')
                ->addFormItem('cover_id', 'cover_config_by_category_id', '封面','',array('info_data'=>$info),array('trigger_from'=>'category_id','from_module'=>'cms'))
                ->addFormItem('list_p_data', 'textarea', '列表显示数据')
                ->addFormItem('status', 'radio', '发布', '发布', array('1' => '是','0' => '否'))
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }



    public function createCmsDocument(){
        $CmsAdminHandleObject = $this->visitor->CmsAdminHandleObject();
        $res = $CmsAdminHandleObject->createCmsDocument();
        if($res['error']==0 && $res['info'] != ""){
            $this->cpk_success($res['info'],array('backurl'=>U('Admin/Cms/document',array('cate_id'=>$res['category_id']))));
        }else{
            $this->cpk_error($res['info']);
        }
    }
    public function saveCmsDocument(){
        $id = I('id',0,'intval');
        $CmsAdminHandleObject = $this->visitor->CmsAdminHandleObject();
        $res = $CmsAdminHandleObject->saveCmsDocument($id);
        if($res['error']==0 && $res['info'] != ""){
            $this->cpk_success($res['info'],array('backurl'=>U('Cms/document',array('cate_id'=>$res['category_id']))));
        }else{
            $this->cpk_error($res['info']);
        }
    }
    public function deleteCmsDocument(){
        $id = I('id',0,'intval');
        $CmsAdminHandleObject = $this->visitor->CmsAdminHandleObject();
        $res = $CmsAdminHandleObject->deleteCmsDocument($id);
        if($res['error'] == 0 && $res['info'] != ""){
            $this->cpk_success($res['info']);
        }else{
            $this->cpk_error($res['info']);
        }
    }


}