<?php
namespace Cms\Datamanager;

class CmsCategoryDatamanager {
	/**
	 * 获取数据
	 */
	public function getControllerActionNameByCatid_NoCache(){

		$deeptwoData = M("cms_category")->where(array('deep'=>2))->select();

		foreach ($deeptwoData as $key => $value) {
			$newData[$value['id']] = $value['name'];
		}
		return $newData;
	}

	public function getCatidByControllerActionName_NoCache($modulename){
		$data = $this->getControllerActionNameByCatid_NoCache($modulename);

		foreach ($data as $key => $value) {
			$newData[$value] = $key;
		}
		return $newData;
	}

	public function getDisplayTplByCatid_NoCache(){
		$deeptwoData = M("cms_category")->where(array('deep'=>2))->select();

		foreach ($deeptwoData as $key => $value) {
			$newData[$value['id']] = $value['display_tpl'];
		}
		return $newData;
	}

	public function getIsPageByCatid_NoCache(){
		$deeptwoData = M("cms_category")->where(array('deep'=>2))->select();

		foreach ($deeptwoData as $key => $value) {
			$newData[$value['id']] = $value['is_page'];
		}
		return $newData;
	}

	/**
	 * 根据catid获取该分类下的所有子分类名称及文章数
	 */
	public function getCateNameListAndArticleNum_catid($catid){
		$sonlist = M("cms_category")->where(array('pid'=>$catid))->select();
		foreach ($sonlist as $key => $value) {
			$sonlist[$key]['articlenum'] = M("cms_document")->where(array("category_id"=>$value['id'],"status"=>1))->count();
			$sonlist[$key]['href_url']   = get_controller_action_name_by_catid($value['id'],"/",MODULE_NAME);
		}
		return $sonlist;
	}

	public function getSubData($pid){
		//$parentData = M("Category")->where(array('id'=>$pid))->find();
        $data = M("cms_category")->where(array('pid'=>$pid,'status'=>1))->order(" sort desc ")->select();
        foreach ($data as $key => $value) {
        	// @todo 确定用那个action_name
        	list($controllername,$actioname) = explode("_", $value['name']);
        	$actioname = $actioname == "" ? "index" : $actioname;
        	// dump($controllername.",".$actioname);

			$data[$key]['controller_name']   = $controllername;
			$data[$key]['action_name']       = $actioname=="" ? 'index' : $actioname;
			$data[$key]['cate_url']          = $controllername."/".$actioname;
			$data[$key]['controller_action'] = $controllername."_".$data[$key]['action_name'];
        }
        return $data;
	}

	public function getSubDataByEnname($enname){
		if($enname=="main"){
			return $this->getSubData(0);
		}
        $id = M("cms_category")->where(array('name'=>$enname,'status'=>1))->getField('id');
        return $this->getSubData($id);
	}


}