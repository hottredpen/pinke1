<?php
namespace Cms\Datamanager;

class CmsBlockDatamanager {

	public function getData($p=1,$page_size=10,$map,$order){
		$data = $this->_takeFormatData("data",$map,$p,$page_size,$order);
		return $data;
	}
	public function getNum($map){
		$data = $this->_takeData("num",$map);
		return (int)$data;
	}
	public function getInfo($id){
		$map['b.status'] = 1;
		$map['b.id']     = $id;
		$data = $this->_takeFormatData("data",$map,1,1);
		return $data[0];
	}

	public function getOneInfoByCategoryName($name){
		$catid = M('cms_block_category')->where(array('name'=>$name))->getField('id');
		if($catid > 0){
			$map['b.status']      = 1;
			$map['b.category_id'] = $catid;
			$data = $this->_takeFormatData("data",$map,1,1,"b.orderid desc");
			return $data[0];
		}
		return array();
	}
	public function getDataListByCategoryName($name,$p=1,$page_size=10){
		$catid = M('cms_block_category')->where(array('name'=>$name))->getField('id');
		if($catid > 0){
			$map['b.status']      = 1;
			$map['b.category_id'] = $catid;
			$data = $this->_takeFormatData("data",$map,$p,$page_size,"b.orderid desc");
			return $data;
		}
		return array();
	}


	/**
     * 多表查询时的相同字段处理
     */
    public function replaceMap($map){
        $replace_arr = array(
            'title'     => 'b.title',
        );
        $newmap = common_replace_dbpre_name_for_leftjoin_map($map,$replace_arr);
        return $newmap;
    }
	private function _takeFormatData($type,$map,$p,$page_size,$order){
		$data = $this->_takeData("data",$map,$p,$page_size,$order);
		foreach ($data as $key => $value) {
			$data[$key]['list_p_data_format'] = cms_format_list_p_data($value['list_p_data']);
			$data[$key]['cover_ids_format']   = common_format_cover_ids($value['cover_ids']);
			$data[$key]['otherdata_format']   = cms_format_otherdata($value['otherdata']);
		}
		return $data;
	}


    private function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order){

		// 采用新的offset_1  来增强 p
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

		$map             = array();
		$map['b.status'] = array("egt",0);
        //合并覆盖
        $newmap = array_merge($map, $searchmap);

        $order = $order == "" ? " b.id desc " : $order;


        if($type=="data"){
            $list = M("cms_block as b")
            		->join('left join '.C('DB_PREFIX').'file AS f on b.cover_id=f.id')
            		->join('left join '.C('DB_PREFIX').'cms_block_category AS c on b.category_id=c.id')
                    ->field('b.*,f.url AS cover_id_format , c.title AS category_id_format')
                    ->where($newmap)
                    ->limit($offset.','.$page_size)
                    ->order($order)
                    ->select();
            foreach ($list as $key => $value) {
            	$list[$key]['content'] = htmlspecialchars_decode($value['content']);
            }


        }else{
            $list = M("cms_block as b")
                    ->field('b.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }


}