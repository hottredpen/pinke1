<?php
namespace Cms\Datamanager;
/**
 * DocumentDatamanager
 * 文档数据管理对象类
 */
class CmsDocumentDatamanager {
	/**
	 * 获取数据
	 */
	public function getDocumentData($p=1,$page_size=20,$map=array(),$order){
		$data = $this->_takeFormatData("data",$map,$p,$page_size,$order);
		return $data;
	}
	/**
	 * 获取数量
	 */
	public function getDocumentNum($map=array()){
		$data = $this->_takeData("num",$map);
		return $data;
	}

	public function getDocumentNum_catid($catid,$searchmap=array()){
		$map = array();
		$map['d.category_id'] = $catid;
		$map['d.status']      = 1;
		//合并覆盖
		$newmap = array_merge($map, $searchmap);
   		$data = $this->_takeData("num",$newmap);
		return $data;
	}

	public function getDocumentData_catid($catid,$p=1,$page_size=20,$searchmap=array()){
		$map                  = array();
		$map['d.category_id'] = $catid;
		$map['d.status']      = 1;
		//合并覆盖
		$newmap = array_merge($map, $searchmap);
   		$data = $this->_takeFormatData("data",$newmap,$p,$page_size);
		return $data;
	}


	public function getDocumentNum_enname($enname,$searchmap=array()){
		$catid = cms_trans_enname_to_category_id($enname);
		return $this->getDocumentNum_catid($catid,$searchmap);
	}

	public function getDocumentData_enname($enname,$p=1,$page_size=20,$searchmap=array()){
		$catid = cms_trans_enname_to_category_id($enname);
		return $this->getDocumentData_catid($catid,$p,$page_size,$searchmap);
	}

	public function getDocumentData_catidArray($catidArray,$p=1,$page_size=20,$searchmap=array()){
		$map                  = array();
		$map['d.category_id'] = array("in",$catidArray);
		$map['d.status']      = 1;
		//合并覆盖
		$newmap = array_merge($map, $searchmap);
   		$data = $this->_takeFormatData("data",$newmap,$p,$page_size);
		return $data;
	}

	public function getDocumentInfoDataOnePage_catid($catid){
		$map                  = array();
		$map['d.category_id'] = $catid;
		$map['d.status']      = 1;
   		$data = $this->_takeFormatData("data",$map,1,1);
   		$info = $data[0];
		return $info;
	}
	public function getDocumentInfoDataOnePage_enname($enname){
		$catid                = cms_trans_enname_to_category_id($enname);
		return $this->getDocumentInfoDataOnePage_catid($catid);
	}
	public function getDocumentInfoData_id($id){
		$map                  = array();
		$map['d.id']          = $id;
		$map['d.status']      = 1;
   		$data = $this->_takeFormatData("data",$map,1,1);
		return $data[0];
	}

	public function getDocumentInfoData_catid_id($catid,$id){
		$map                  = array();
		$map['d.id']          = $id;
		$map['d.category_id'] = $catid;
		$map['d.status']      = 1;
   		$data = $this->_takeFormatData("data",$map,1,1);

   		$info = $data[0];
		// 上一篇
		$pre_map['d.status']      = array('eq', 1);
		$pre_map['d.id']          = array('lt', $info['id']);
		$pre_map['d.category_id'] = $catid;
		$previous                 = $this->_takeFormatData("data",$pre_map,1,1,' d.id desc ');

		// 下一篇
		$next_map['d.status']      = array('eq', 1);
		$next_map['d.id']          = array('gt', $info['id']);
		$next_map['d.category_id'] = $catid;
		$next                      = $this->_takeFormatData("data",$next_map,1,1,' d.id asc ');

		$info['previous'] = $previous[0];
		$info['next']     = $next[0];
 		return $info;
	}
	public function getDocumentInfoData_enname_id($enname,$id){
		$catid = cms_trans_enname_to_category_id($enname);
		return $this->getDocumentInfoData_catid_id($catid,$id);
	}
	public function getDocumentData_catidArray_keywordsArray($catidArray=array(),$keywordsArray=array(),$p=1,$page_size=10){
		//目前就取前一个 @todo
		$keywords               = $keywordsArray[0];
		
		$map                    = array();
		$map['d.category_id']   = array("in",$catidArray);
		$map['d.status']        = 1;
		$where                  = array();
		$where['d.title']       = array("like","%".$keywords."%");
		$where['d.description'] = array("like","%".$keywords."%");
		$where['_logic']        = 'or';
		$map['_complex']        = $where;
		$data                   = $this->_takeFormatData("data",$map,$p,$page_size);
		return $data;
	}

	private function _takeFormatData($type,$map=array(),$p=1,$page_size=20,$order){
		$data = $this->_takeData($type,$map,$p,$page_size,$order);
		foreach ($data as $key => $value) {
			$data[$key]['href_url']  = MODULE_NAME."/".$this->_get_controller_action_name_by_catid($value['category_id'],"/",MODULE_NAME);
			if($value['cover_id'] > 0){
				$data[$key]['cover_url']       = M("file")->where(array("id"=>$value['cover_id']))->getField("url");
				$data[$key]['cover_id_format'] = $data[$key]['cover_url']; // 这个是规范的写法
			}
		}
		return $data;
	}

	// public function formatListData($listdata){
	// 	$dataArray  = array_filter(explode("\n", $listdata));

	// 	foreach ($dataArray as $key => $value) {

	// 		$dataArray[$key] = str_replace("{{QQ_1}}", '<a href="http://wpa.qq.com/msgrd?v=3&uin=2742974934&site=qq&menu=yes" class="icon_qq_24" target="_blank"><img src="http://wpa.qq.com/pa?p=2:2742974934:50" alt="手绘咨询" /></a>', $dataArray[$key]);
	// 		$dataArray[$key] = str_replace("{{QQ_2}}", '<a href="http://wpa.qq.com/msgrd?v=3&uin=2742974934&site=qq&menu=yes" class="icon_qq_24" target="_blank"><img src="http://wpa.qq.com/pa?p=2:2742974934:50" alt="手绘咨询" /></a>', $dataArray[$key]);
	// 		$dataArray[$key] = str_replace("{{网上报名}}", $signuphtml, $dataArray[$key]);



	// 		if(strstr($value,"=>url=>")){
	// 			list($txt,$url) = explode("=>url=>", $value);
	// 			$dataArray[$key] = "<a href='".$url."' >".$txt."</a>";
	// 		}

	// 		if(strstr($value,"=>img=>")){
	// 			list($txt,$img) = explode("=>img=>", $value);
	// 			$dataArray[$key] = "<a title='".$txt."' ><img width='100' src='".$img."' alt='".$txt."'></a>";
	// 		}

	// 		if(strstr($value,"=>fa=>")){
	// 			list($txt,$faclass) = explode("=>fa=>", $value);
	// 			$dataArray[$key] = "<i class='fa ".$faclass."'></i>&nbsp;&nbsp;".$txt."";
	// 		}

	// 		if(strstr($value,"=>car_price=>")){
	// 			list($txt,$pr_value) = explode("=>car_price=>", $value);
	// 			$dataArray[$key] = "<a href='".U('car/buycar/index',array('pr'=>$pr_value))."' >".$txt."</a>";
	// 		}

	// 	}
	// 	return $dataArray;
	// }

	// public function formatOtherData($listdata){
	// 	$dataArray  = array_filter(explode("\n", $listdata));
	// 	foreach ($dataArray as $key => $value) {
	// 		list($txt,$url) = explode("=>", $value);
	// 		$dataArray[$txt] = $url;
	// 	}
	// 	return $dataArray;
	// }

	/**
     * 多表查询时的相同字段处理
     */
    public function replaceMap($map){
        $replace_arr = array(
            'title'     => 'd.title',
            'status'    => 'd.status'
        );
        $newmap = common_replace_dbpre_name_for_leftjoin_map($map,$replace_arr);
        return $newmap;
    }


	private function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order){
		// 采用新的offset:1  来增强 p
		// 取消原有的 page($p.','.$page_size)
		// 采用新的 	limit($offset.','.$page_size)
		if(strstr($p,'offset')){
			$_o_arr = explode("_", $p);
			$offset = (int)$_o_arr[1];
		}else{
			$offset = ($p - 1) * $page_size;
			if($offset < 0){
				$offset = 0;
			}
		}

		$map = array();
		//$map['o.status'] = array("egt",0);
		//合并覆盖
		$newmap = array_merge($map, $searchmap);

		$order = $order == "" ? " d.id desc " : $order;

		if($type=="data"){
			$list = M("cms_document as d")
					->join('left join '.C('DB_PREFIX').'cms_document_article AS a on d.id=a.id')
					->join('left join '.C('DB_PREFIX').'cms_category AS c on d.category_id=c.id')
					->field('d.*,a.content,c.name AS category_name,c.title AS category_title')
					->where($newmap)
					->order($order)
					->limit($offset.','.$page_size)
					->select();

				foreach ($list as $key => $value) {
					$list[$key]['content']   = htmlspecialchars_decode($value['content']);
				}
		}else{
			$list = M("cms_document as d")
					->join('left join '.C('DB_PREFIX').'cms_document_article AS a on d.id=a.id')
					->field('d.id')
					->where($newmap)
					->count();
		}
        return $list;
	}


	private function _get_controller_action_name_by_catid($catid,$explodstr="_",$modulename){
	    if(!F("CmsControllerActionNameByCatid")){
			$data = M("cms_category")->where(array('deep'=>2))->getField("id,name");
			F("CmsControllerActionNameByCatid",$data);
	    }
	    $data = F("CmsControllerActionNameByCatid");
	    if($explodstr != "_"){
	        foreach ($data as $key => $value) {
	            $data[$key] = str_replace("_", $explodstr, $data[$key]);
	        }
	    }
	    if($catid>0){
	        return $data[$catid];
	    }
	    return $data;
	}

}