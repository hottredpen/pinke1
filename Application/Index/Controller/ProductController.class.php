<?php
namespace Index\Controller;
class ProductController extends ApiBaseController {

    protected function _initialize() {
    	parent::_initialize();
    }


    public function products(){
        $page      = I('page',1,'intval');
        $pageSize  = I('pageSize',15,'intval');
        $map       = array();

        $data_list = D('Cms/CmsProduct','Datamanager')->getDataForApp($page,$pageSize,$map);
        $data_num  = D('Cms/CmsProduct','Datamanager')->getNum($map);

        $data                 = array();
        $data['total']        = $data_num;
        $data['per_page']     = $pageSize;
        $data['current_page'] = $page;
        $data['last_page']    = ceil($data_num/$pageSize) > 0 ? ceil($data_num/$pageSize) : 1;
        $data['data']         = $data_list;


        $result['code']  = 200;
        $result['msg']   = "成功";
        $result['data']  = $data;
        $this->json($result);
    }

    public function categorys(){
        $page      = I('page',1,'intval');
        $pageSize  = I('pageSize',15,'intval');
        $map       = array();

        $data_list = D('Cms/CmsProductCategory','Datamanager')->getDataForApp($page,$pageSize,$map);
        $data_num  = D('Cms/CmsProductCategory','Datamanager')->getNum($map);

        $data                 = array();
        $data['total']        = $data_num;
        $data['per_page']     = $pageSize;
        $data['current_page'] = $page;
        $data['last_page']    = ceil($data_num/$pageSize) > 0 ? ceil($data_num/$pageSize) : 1;
        $data['data']         = $data_list;


        $result['code']  = 200;
        $result['msg']   = "成功";
        $result['data']  = $data;
        $this->json($result);
    }

    public function recommendProducts(){
        $page      = I('page',1,'intval');
        $pageSize  = I('pageSize',15,'intval');
        $map       = array();

        $data_list = D('Cms/CmsProduct','Datamanager')->getDataForApp($page,$pageSize,$map);
        $data_num  = D('Cms/CmsProduct','Datamanager')->getNum($map);

        $data                 = array();
        $data['total']        = $data_num;
        $data['per_page']     = $pageSize;
        $data['current_page'] = $page;
        $data['last_page']    = ceil($data_num/$pageSize) > 0 ? ceil($data_num/$pageSize) : 1;
        $data['data']         = $data_list;


        $result['code']  = 200;
        $result['msg']   = "成功";
        $result['data']  = $data;
        $this->json($result);
    }

    public function whatwedo(){
        $page      = I('page',1,'intval');
        $pageSize  = I('pageSize',15,'intval');
        $map       = array();

        $data_list = D('Cms/CmsProduct','Datamanager')->getDataForApp($page,$pageSize,$map);
        $data_num  = D('Cms/CmsProduct','Datamanager')->getNum($map);

        $data                 = array();
        $data['total']        = $data_num;
        $data['per_page']     = $pageSize;
        $data['current_page'] = $page;
        $data['last_page']    = ceil($data_num/$pageSize) > 0 ? ceil($data_num/$pageSize) : 1;
        $data['data']         = $data_list;


        $result['code']  = 200;
        $result['msg']   = "成功";
        $result['data']  = $data;
        $this->json($result);
    }

}