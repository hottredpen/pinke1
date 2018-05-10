<?php
namespace Index\Controller;
class WebController extends ApiBaseController {

    protected function _initialize() {
    	parent::_initialize();
    }

    public function index(){

        // about
        $about_info = D('Cms/CmsBlock','Datamanager')->getOneInfoByCategoryName('index_about');
        $about_info = $this->format_about_data($about_info);

        // contact
        $contact_info = D('Cms/CmsBlock','Datamanager')->getOneInfoByCategoryName('index_contact');
        $contact_info = $this->format_contact_data($contact_info);

        // product
        $product_data = D('Cms/CmsProduct','Datamanager')->getDataForApp($page,$pageSize,$map);


        $data['about']   = $about_info;
        $data['contact'] = $contact_info;
        $data['product'] = $product_data;

        $result['code']  = 200;
        $result['msg']   = "成功";
        $result['data']  = $data;
        $this->json($result);



    }

    private function format_about_data($info){
        $newInfo['title']              = $info['title'];
        $newInfo['list_p_data_format'] = $info['list_p_data_format'];
        $newInfo['otherdata_format']   = $info['otherdata_format'];
        return $newInfo;
    }

    private function format_contact_data($info){
        $newInfo['title']              = $info['title'];
        $newInfo['list_p_data_format'] = $info['list_p_data_format'];
        $newInfo['otherdata_format']   = $info['otherdata_format'];
        return $newInfo;
    }

}