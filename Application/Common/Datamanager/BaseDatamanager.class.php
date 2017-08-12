<?php
namespace Common\Datamanager;
/**
 * DocumentDatamanager
 * 文档数据管理对象类 @todo
 */
class BaseDatamanager {

    private $_page;
    private $_page_size;
    private $_map;
    private $_order;


 	function __construct() {

	}


	private $_init_map = array();


	protected function init(){

	}

	protected function init_data(){

	}

    // base
	// public function getListData($p=1,$page_size=20,$map,$order){
 //        $data  = $this->_takeData("data",$map,$p,$page_size,$order);
 //        return $data;
	// }

	/**
	 * 获取单条数据
	 */
	public function getInfoData($id){

	}

    public function _takeData($type="data",$searchmap,$p=1,$page_size=20,$order='r.id desc'){
        $map             = array();
        $map['r.id']       = array("egt",0);
        //合并覆盖
        $newmap = array_merge($map, $searchmap);
        $newmap['r.public_id'] = weixin_session_public_id(); // 不允许被$searchmap替换


        if($type=="data"){

        }else{

        }
        return $list;
    }


}