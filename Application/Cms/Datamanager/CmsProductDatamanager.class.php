<?php
namespace Cms\Datamanager;

class CmsProductDatamanager {

	public function getData($p=1,$page_size=10,$map,$order){
		$data = $this->_takeFormatData("data",$map,$p,$page_size,$order);
		return $data;
	}
	public function getNum($map){
		$data = $this->_takeData("num",$map);
		return (int)$data;
	}
	public function getInfo($id){
		$map['p.status'] = 1;
		$map['p.id']     = $id;
		$data = $this->_takeFormatData("data",$map,1,1);
		return $data[0];
	}
	/**
     * 多表查询时的相同字段处理
     */
    public function replaceMap($map){
        $replace_arr = array(
            'title'     => 'p.title',
        );
        $newmap = common_replace_dbpre_name_for_leftjoin_map($map,$replace_arr);
        return $newmap;
    }

	private function _takeFormatData($type,$map,$p,$page_size,$order){
		$data = $this->_takeData("data",$map,$p,$page_size,$order);
		foreach ($data as $key => $value) {
			$data[$key]['list_p_data_format'] = cms_format_list_p_data($value['list_p_data']);
			$data[$key]['cover_ids_format']   = common_format_cover_ids($value['cover_ids']);
			$data[$key]['href_url']           =  U(MODULE_NAME."/product/".cms_local_product_category_id_name($value['category_id']));
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
		$map['p.status'] = array("egt",0);
        //合并覆盖
        $newmap = array_merge($map, $searchmap);

        $order = $order == "" ? " p.id desc " : $order;

        if($type=="data"){
            $list = M("cms_product as p")
            		->join('left join '.C('DB_PREFIX').'file AS f on p.cover_id=f.id')
            		->join('left join '.C('DB_PREFIX').'cms_product_category AS c on p.category_id=c.id')
                    ->field('p.*,f.url AS cover_id_format , c.title AS category_id_format')
                    ->where($newmap)
                    ->limit($offset.','.$page_size)
                    ->order($order)
                    ->select();
            foreach ($list as $key => $value) {
            	$list[$key]['content'] = htmlspecialchars_decode($value['content']);
            }


        }else{
            $list = M("cms_product as p")
                    ->field('p.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }


}