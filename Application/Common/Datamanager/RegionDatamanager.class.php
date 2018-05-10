<?php
namespace Common\Datamanager;

class RegionDatamanager {



    public $model;

    function __construct() {
        $this->model = M("Region");
    }
    /**
     * 根据catid获取多级的selectHtml
     */
    public function selectHtml_catid($catid,$ele_id="#j_select_city",$is_show_this=true){
        header("Content-Type: text/html; charset=utf-8");
        $thiscatinfo = $this->model->where(array("id"=>$catid))->find();
        if($thiscatinfo['deep'] == 1){
            $select_html = $this->_select_html(array($catid.",0,1"),$ele_id);
        }else{
            $allpid_arr = array();
            $thispid    = $thiscatinfo['pid'];
            $selectnum  = (int)($thiscatinfo['deep']-1);
            for ($i=0; $i < $selectnum; $i++) { 
                $getparentData  = $this->model->where(array("id"=>$thispid))->field("pid,id,deep")->find();
                $thispid        = $getparentData['pid'];
                $allpid_arr[$i] = $getparentData['id'].",".$getparentData['pid'].",".$getparentData['deep'];
            }
            if($is_show_this){
                $allpid_arr[$selectnum] = $thiscatinfo['id'].",".$thiscatinfo['pid'].",".$thiscatinfo['deep'];
            }
            $select_html = $this->_select_html($allpid_arr,$ele_id);
        }
        return $select_html;
    }

    private function _select_html($allpid_arr,$ele_id){
        asort($allpid_arr);
        $str_arr = array();
        $outstr  = "";
        foreach ($allpid_arr as $key => $value) {
            list($id,$pid,$deep) = explode(",", $value);
            $str_arr[$deep]= $this->_template($id,$pid,$deep,$ele_id);
        }
        sort($str_arr);
        return implode("", $str_arr);
    }

    private function _template($id,$pid,$deep,$ele_id){

        $data = $this->model->where(array("pid"=>$pid))->select();

        $str = '<div class="col-md-2 pl0">
                <select name="" deep="'.$deep.'" data-selectid="'.$ele_id.'" class="j_select_change form-control input-sm j_select_forcate">';
        $str .= '<option value="-1">请选择</option>';
                foreach ($data as $key => $value) {
                    if($value['id'] == $id){
                        $str .= '<option value="'.$value['id'].'" selected>'.$value['name'].'</option>';
                    }else{
                        $str .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                    }
                }
        $str .= '</select>
                </div>';
        return $str;
    }
}