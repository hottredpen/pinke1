<?php
namespace Admin\Admin;

class AdminUploadconfigAdmin extends AdminBaseAdmin {
    
    public function _initialize() {
        parent::_initialize();
    }

    public function uploadconfig(){
        $p         = I('p',1,'intval');
        $page_size = 20;

        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list = D('Admin/AdminUploadconfig','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num  = D('Admin/AdminUploadconfig','Datamanager')->getNum($map);

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('上传配置列表')
                ->ajax_url(U('Admin/AdminUploadconfig/ajaxAdminUploadconfig'))
                ->addTopButton('layer',array('data-action'=>'addAdminUploadconfig','data-width'=>"800px",'data-height'=>'1050px','data-title'=>'新增-上传配置'))
                ->setSearch(array('name'=>'附件名称','typename'=>'目录标示'),'',U('Admin/AdminUploadconfig/index'))
                ->addOrder('typename')
                ->addFilter('sub_type','function','admin_local_upload_sub_type_name')
                ->addFilter('scale_type','function','admin_local_upload_scale_type_name')
                ->addFilter('upload_return_type','function','admin_local_upload_return_type_name')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('name', '附件名称')
                ->addTableColumn('typename', '目录标示')
                ->addTableColumn('from_module', '所属模块')
                ->addTableColumn('catid', 'catid','ajax_edit')
                ->addTableColumn('maxsize', '最大上传大小(MB)','ajax_edit')
                ->addTableColumn('allowext', '允许的格式','ajax_edit')
                ->addTableColumn('sub_type', '裁剪的类型','function','admin_local_upload_sub_type_name')
                ->addTableColumn('sub_width', '裁剪的长')
                ->addTableColumn('sub_height', '裁剪的宽')
                ->addTableColumn('scale_type', '放缩的类型','function','admin_local_upload_scale_type_name')
                ->addTableColumn('scale_width', '放缩的长')
                ->addTableColumn('scale_height', '放缩的宽')
                ->addTableColumn('upload_return_type', '上传后返回图','function','admin_local_upload_return_type_name')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->addRightButton('layer',array('data-action'=>'editAdminUploadconfig','data-width'=>"800px",'data-height'=>'1050px','data-title'=>'编辑-上传配置'))
                ->addRightButton('delete_confirm',array('data-action'=>'deleteAdminUploadconfig','data-itemname'=>'上传配置'))
                ->setPage($data_num,$page_size)
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function addAdminUploadconfig(){
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('新增上传配置')
                ->setPostUrl(U('Admin/Admin/createAdminUploadconfig'))
                ->addFormItem('name', 'text', '名称', '')
                ->addFormItem('from_module', 'text', '所属模块', '')
                ->addFormItem('typename', 'text', '存储目录名', '')
                ->addFormItem('catid', 'text', 'catid(文章类)', '')
                ->addFormItem('maxsize', 'text', '最大尺寸', 'M')
                ->addFormItem('allowext', 'text', '允许的格式', '')
                ->addFormItem('sub_type', 'radio', '截取类型', '',admin_local_upload_sub_type_name())
                ->addFormItem('sub_width', 'text', '截取宽度', 'px')
                ->addFormItem('sub_height', 'text', '截取高度', 'px')
                ->addFormItem('scale_type', 'radio', '放缩类型', '',admin_local_upload_scale_type_name())
                ->addFormItem('scale_width', 'text', '放缩宽度', 'px')
                ->addFormItem('scale_height', 'text', '放缩高度', 'px')
                ->addFormItem('upload_return_type', 'radio','上传后返回图片类型', '',admin_local_upload_return_type_name())
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }
    public function editAdminUploadconfig(){
        $id          = I('id',0,'intval');
        $info        = D('Admin/AdminUploadconfig','Datamanager')->getInfo($id);
        $builder     = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('修改上传配置')
                ->setPostUrl(U('Admin/Admin/updateAdminUploadconfig'))
                ->addFormItem('name', 'text', '名称', '')
                ->addFormItem('from_module', 'text', '所属模块', '')
                ->addFormItem('typename', 'text', '存储目录名', '')
                ->addFormItem('catid', 'text', 'catid(文章类)', '')
                ->addFormItem('maxsize', 'text', '最大尺寸', 'M')
                ->addFormItem('allowext', 'text', '允许的格式', '')
                ->addFormItem('sub_type', 'radio', '截取类型', '',admin_local_upload_sub_type_name())
                ->addFormItem('sub_width', 'text', '截取宽度', 'px')
                ->addFormItem('sub_height', 'text', '截取高度', 'px')
                ->addFormItem('scale_type', 'radio', '放缩类型', '',admin_local_upload_scale_type_name())
                ->addFormItem('scale_width', 'text', '放缩宽度', 'px')
                ->addFormItem('scale_height', 'text', '放缩高度', 'px')
                ->addFormItem('upload_return_type', 'radio', '上传后返回图片类型' ,'',admin_local_upload_return_type_name())
                ->setFormData($info)
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function createAdminUploadconfig(){
        $AdminAdminHandleObject = $this->visitor->AdminAdminHandleObject();
        $res = $AdminAdminHandleObject->createAdminUploadconfig();
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info']);
        }else{
            $this->pk_error($res['info']);
        }
    }

    public function updateAdminUploadconfig(){
        $AdminAdminHandleObject = $this->visitor->AdminAdminHandleObject();
        $res = $AdminAdminHandleObject->updateAdminUploadconfig();
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info']);
        }else{
            $this->pk_error($res['info']);
        }
    }

    public function deleteAdminUploadconfig(){
        $AdminAdminHandleObject = $this->visitor->AdminAdminHandleObject();
        $res = $AdminAdminHandleObject->deleteAdminUploadconfig();
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info']);
        }else{
            $this->pk_error($res['info']);
        }
    }

}