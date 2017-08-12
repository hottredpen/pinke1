<?php 
namespace Admin\Controller;

class CmsController extends BackController {

    protected function _initialize() {
        parent::_initialize();
        // 如果找不到方法，重新定义到新的Admin控制器  方法内的参数请用I()方法传参（多次重定向的后遗症）
        $this->action_list =  array(

            // setting
            'setting'               => 'CmsBase/setting',
            'saveCmsSetting'        => 'CmsBase/saveCmsSetting',

            // seo
            'seo'                   => 'CmsSeo/index',
            'addCmsSeo'             => 'CmsSeo/addCmsSeo',
            'editCmsSeo'            => 'CmsSeo/editCmsSeo',

            // category
            'category'              => 'CmsCategory/index',
            'addCmsCategory'        => 'CmsCategory/addCmsCategory',
            'editCmsCategory'       => 'CmsCategory/editCmsCategory',


            // document
            'document'              => 'CmsDocument/index',
            'addCmsDocument'        => 'CmsDocument/addCmsDocument',
            'editCmsDocument'       => 'CmsDocument/editCmsDocument',
            'createCmsDocument'     => 'CmsDocument/createCmsDocument',
            'saveCmsDocument'       => 'CmsDocument/saveCmsDocument',
            'deleteCmsDocument'     => 'CmsDocument/deleteCmsDocument',

            // product
            'product'               => 'CmsProduct/index',
            'addCmsProduct'         => 'CmsProduct/addCmsProduct',
            'editCmsProduct'        => 'CmsProduct/editCmsProduct',


            // product_category
            'product_category'       => 'CmsProductCategory/index',
            'addCmsProductCategory'  => 'CmsProductCategory/addCmsProductCategory',
            'editCmsProductCategory' => 'CmsProductCategory/editCmsProductCategory',

            // 
            'webmsg'                => 'CmsMessage/webmsg',
            'servicemsg'            => 'CmsMessage/servicemsg',

            // 
            'block_category'       => 'CmsBlockCategory/block_category',
            'addCmsBlockCategory'  => 'CmsBlockCategory/addCmsBlockCategory',
            'editCmsBlockCategory' => 'CmsBlockCategory/editCmsBlockCategory',


            'block'            => 'CmsBlock/block',
            'addCmsBlock'      => 'CmsBlock/addCmsBlock',
            'editCmsBlock'     => 'CmsBlock/editCmsBlock',

        );


    }

}